#!/usr/bin/python3
# @Мартин.
# ███████╗              ██╗  ██╗    ██╗  ██╗     ██████╗    ██╗  ██╗     ██╗    ██████╗
# ██╔════╝              ██║  ██║    ██║  ██║    ██╔════╝    ██║ ██╔╝    ███║    ╚════██╗
# ███████╗    █████╗    ███████║    ███████║    ██║         █████╔╝     ╚██║     █████╔╝
# ╚════██║    ╚════╝    ██╔══██║    ╚════██║    ██║         ██╔═██╗      ██║     ╚═══██╗
# ███████║              ██║  ██║         ██║    ╚██████╗    ██║  ██╗     ██║    ██████╔╝
# ╚══════╝              ╚═╝  ╚═╝         ╚═╝     ╚═════╝    ╚═╝  ╚═╝     ╚═╝    ╚═════╝

from flask import Flask, request, jsonify, render_template, send_from_directory, abort
import socket
import argparse

app = Flask(__name__)

 
parser = argparse.ArgumentParser(description='Run the Flask IoT application.')
parser.add_argument('-iot', '--ip', type=str, required=True, help='Specify the IP address to connect to.')
args = parser.parse_args()

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/send', methods=['POST'])
def send_payload():
    payload = request.json.get('payload')
    if payload:
        try:
            with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
                s.connect((args.ip, 10032))  # Use the specified IP address
                s.sendall((payload + '\n').encode('utf-8'))   
                return jsonify({'message': 'Payload sent successfully!'}), 200
        except Exception as e:
            return jsonify({'message': f'Failed to send: {str(e)}'}), 500
    return jsonify({'message': 'Invalid payload.'}), 400

@app.route('/file/<path:filename>', methods=['GET'])
def download_file(filename):
    if filename.endswith('.zip'):
        return send_from_directory('file', filename)
    else:
        abort(404)

if __name__ == '__main__':
    app.run(debug=False, host='0.0.0.0', port=5000)
