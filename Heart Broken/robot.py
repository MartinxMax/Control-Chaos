#!/usr/bin/python3
# @Мартин.

LOGO='''
# ███████╗              ██╗  ██╗    ██╗  ██╗     ██████╗    ██╗  ██╗     ██╗    ██████╗
# ██╔════╝              ██║  ██║    ██║  ██║    ██╔════╝    ██║ ██╔╝    ███║    ╚════██╗
# ███████╗    █████╗    ███████║    ███████║    ██║         █████╔╝     ╚██║     █████╔╝
# ╚════██║    ╚════╝    ██╔══██║    ╚════██║    ██║         ██╔═██╗      ██║     ╚═══██╗
# ███████║              ██║  ██║         ██║    ╚██████╗    ██║  ██╗     ██║    ██████╔╝
# ╚══════╝              ╚═╝  ╚═╝         ╚═╝     ╚═════╝    ╚═╝  ╚═╝     ╚═╝    ╚═════╝
'''
import sys
import serial
import serial.tools.list_ports
import threading
import time
from PyQt5.QtWidgets import QApplication, QWidget, QLabel, QVBoxLayout, QMessageBox, QFrame
from PyQt5.QtGui import QFont

baudrate = 9600
selected_port = None
serial_thread = None

def get_serial_ports():
    ports = serial.tools.list_ports.comports()
    return [(port.device, port.description) for port in ports]

def confirm_and_start_ui():
    global selected_port
    start_time = time.time()

    print("\033[93mPlease insert the device within 10 seconds...\033[0m")
    
    while time.time() - start_time < 10:
        ports = get_serial_ports()
        if ports:
            break
        time.sleep(1)
    
    if not ports:
        print("\033[91mNo available serial ports detected within 10 seconds, exiting program.\033[0m")
        QMessageBox.critical(None, "Error", "No available serial ports detected within 10 seconds, exiting program.")
        sys.exit()
    
    print("\033[92mAvailable Serial Ports:\033[0m")
    for index, (port, desc) in enumerate(ports):
        print(f"\033[94m{index + 1}: {port} - {desc}\033[0m")
    
    choice = -1
    while choice < 0 or choice >= len(ports):
        try:
            choice = int(input("Please select the port number: ")) - 1
            if 0 <= choice < len(ports):
                selected_port = ports[choice][0]
                print(f"\033[92mSelected Port: {selected_port}\033[0m")
                start_serial_thread()
                launch_gui()
            else:
                print("\033[91mInvalid selection.\033[0m")
        except ValueError:
            print("\033[91mPlease enter a valid number.\033[0m")

def start_serial_thread():
    global serial_thread
    serial_thread = threading.Thread(target=read_serial, daemon=True)
    serial_thread.start()

def read_serial():
    if selected_port:
        try:
            with serial.Serial(selected_port, baudrate, timeout=1) as ser:
                while True:
                    data = ser.readline().decode('utf-8').strip()
                    if data:
                        if 'LCD:' in data:
                            lcd_data = data.split('LCD:')[1]
                            update_display(lcd_data)
                            if 'SLEEP...' in lcd_data:
                                update_power_status(1)
                        elif 'PWR:' in data:
                            power_status = data.split('PWR:')[1]
                            update_power_status(int(power_status))
                    time.sleep(0.1)
        except Exception as e:
            print(f"\033[91mSerial Error: {e}\033[0m")

def update_display(text):
    display_label.setText(text)

def update_power_status(status):
    if status == 1:
        power_label.setStyleSheet("background-color: #4CAF50; color: white; font-size: 24px;")
        power_label.setText("Power Status: Running")
    elif status == 0:
        power_label.setStyleSheet("background-color: #F44336; color: white; font-size: 24px;")
        power_label.setText("Power Status: Stopped")
    else:
        power_label.setStyleSheet("background-color: #9E9E9E; color: white; font-size: 24px;")
        power_label.setText("Power Status: Unknown")

def launch_gui():
    window.showFullScreen()  

print(LOGO)
 
app = QApplication(sys.argv)
window = QWidget()
window.setWindowTitle("IoT Robot LCD Display")
layout = QVBoxLayout(window)

 
lcd_frame = QFrame()
lcd_frame.setStyleSheet("background-color: black; border: 2px solid #00FF00; border-radius: 10px; padding: 20px;")
display_label = QLabel("Waiting for data...")
display_label.setStyleSheet("color: #00FF00; font-size: 72px;")   
display_label.setFont(QFont("Courier New", 72))   
lcd_layout = QVBoxLayout(lcd_frame)
lcd_layout.addWidget(display_label)
layout.addWidget(lcd_frame)

 
power_frame = QFrame()
power_frame.setStyleSheet("background-color: #E0E0E0; border-radius: 10px; padding: 10px;")
power_label = QLabel("Power Status: Unknown", power_frame)
power_label.setStyleSheet("background-color: gray; color: white; font-size: 24px;")
power_layout = QVBoxLayout(power_frame)
power_layout.addWidget(power_label)
layout.addWidget(power_frame)

layout.setStretch(0, 3)   
layout.setStretch(1, 1)   

 
confirm_and_start_ui()

 
sys.exit(app.exec_())
