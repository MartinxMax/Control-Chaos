 
# *Toxic Cascade*

<p align="center">
 
  <img src="https://img.shields.io/badge/Category-CTF-darkgreen" alt="CTF" style="margin-right: 10px;">
  <img src="https://img.shields.io/badge/Team-S--H4CK13@Maptnh-darkmagenta" alt="S-H4CK13" style="margin-right: 10px;">

  <img src="https://img.shields.io/badge/Mechanical-Reverse%20Engineering-brightblue" alt="Mechanical Reverse Engineering" style="margin-right: 10px;">
  <img src="https://img.shields.io/badge/Chemical-Chemistry-brightgreen" alt="Chemistry" style="margin-right: 10px;">
  <img src="https://img.shields.io/badge/Story-Plot%20Decryption-blueviolet" alt="Plot Decryption" style="margin-right: 10px;">

  <img src="https://img.shields.io/badge/Category-APT-darkred" alt="APT" style="margin-right: 10px;">
</p>


![aa](./pic/TC.jpg)

**Recommended Number of Participants: 1**



## Deployment

Copy `./web/` to the web server.

Access the task panel at `http://<IP>`.

![alt text](./pic/image.png)

In `./target/reset.py`, set the DingTalk bot TOKEN and signature (used to monitor whether the contestant has pwned this chemical wastewater treatment plant). This script will reset the data in the DCS control panel to avoid data freeze. When the contestant clears the task, a success message will be sent via the DingTalk bot, along with the contestant's failure count.

Run the command:

`$ python reset.py`

![alt text](./pic/image-1.png)

![alt text](./pic/image-2.png)

 