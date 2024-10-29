import time
import sys
sys.path.append('.')
import requests
from pack.DingTalkPush import DingTalk

TK = DingTalk(False)
 
url = "http://127.0.0.1/target/reset.php"
pwned_url = "http://127.0.0.1/target/reset.php?pwned=1"
res_ip_url = "http://127.0.0.1/target/data/fail.json"
pwned_check_url = "http://127.0.0.1/target/data/pwned.json"

reset_frequency = 10
pwned_access_time = 20


TK = DingTalk(False)
TK.set_token('')
TK.set_secret('')
TK.send_text('Starting....')


while True:
    try:
        pwned_response = requests.get(pwned_check_url)
        pwned_data = pwned_response.json()

        if pwned_data.get("pwned") == 1:
            TK.send_text('Target machine has been PWNed!!!!')
            time.sleep(1)
            ip_response = requests.get(res_ip_url)
            TK.send_text('Fail => '+str(ip_response.text))
            print("The target machine status has changed to pwned; delaying access to pwned_url.")
            time.sleep(pwned_access_time)
            response = requests.post(pwned_url)
            print("Resetting target machine clearance data pwned.")
        else:
            response = requests.post(url)
            print("Resetting pump data.")

        if response.status_code == 200:
            print("Data has been restored.")
        else:
            print(f"Request failed, status code: {response.status_code}")

    except Exception as e:
        print(f"An error occurred: {e}")

    time.sleep(reset_frequency)
