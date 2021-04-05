# Base-station Code

import concurrent.futures  # thread
import datetime  # waktu
import serial  # akses serial port
import RPi.GPIO as GPIO  # input output
import mysql.connector  # connect python dengan mysql
from mysql.connector import Error


GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
# initial serial
ser = serial.Serial(
    port='/dev/ttyS0',
    baudrate=9600,
    timeout=1
)


def getData(x):
    parsed = x.split("|")
    if len(parsed) > 1:
        node = parsed[0]
        detak = parsed[1]
        oksigen = parsed[2]
        suhu = parsed[3]

    print(node, detak, oksigen, suhu)

    localtime = datetime.now()
    print(localtime)
    #localtime = localtime.strftime('%Y-%m-%d %H:%M:%S')
    return node, detak, oksigen, suhu, localtime


POOL_SIZE = 2


def InsertDb(x):
    db = mysql.connector.connect(
        host='localhost',
        database='coba',
        user='admin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )

    cursor = db.cursor(buffered=True)
    node = x[0]
    detak = x[1]
    oksigen = x[2]
    suhu = x[3]
    localtime = [4]

    query = (
        "INSERT INTO sense (waktu, node, detak, oksigen, suhu) VALUES (%s, %s, %s, %s, %s)")
    values = (localtime, node, detak, oksigen, suhu)
    cursor.execute(query, values)

    db.commit()
    cursor.close()
    db.close()


while 1:
    x = ser.readline().decode("ascii").strip()
    with concurrent.futures.ThreadPoolExecutor() as executor:
        f1 = executor.submit(getData, x)
        print(f1)

        if f1.result != None:
            f2 = executor.submit(InsertDb, f1.result())
            print(f2.result())
