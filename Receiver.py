# Base-station Code

import concurrent.futures  # threads
import datetime  # waktu
import serial  # akses serial port
import mysql.connector  # connect python dengan mysql
from mysql.connector import Error


# GPIO.setmode(GPIO.BCM)
# GPIO.setwarnings(False)
# GPIO.setup(23, GPIO.OUT)

# variabel
appRunning = True
menuShow = True
sensing = True
counter = 0

# initial serial
s = serial.Serial(
    port='/dev/ttyUSB0',
    baudrate=9600,
    parity=serial.PARITY_NONE,
    stopbits=serial.STOPBITS_ONE,
    bytesize=serial.EIGHTBITS,
    timeout=1
)

# default tampilan
print("Aplikasi Base Station Berjalan")
print("----------------------")
print("Daftar Menu Perintah : ")
print("1. Check status Node")
print("2. Mulai Pemeriksaan")
print("3. Stop Pemeriksaan")
print("4. Keluar dari Aplikasi")
print("----------------------")
print("Silahkan Input Nomor Perintah : ")

perintah = input()
# print(perintah)


def mainMenu():
    print("masuk function mainMenu")
    print("Aplikasi Base Station Berjalan")
    print("----------------------")
    print("Daftar Menu Perintah : ")
    print("1. Check status Node")
    print("2. Mulai Pemeriksaan")
    print("3. Stop Pemeriksaan")
    print("4. Keluar dari Aplikasi")
    print("----------------------")
    print("Silahkan Input Nomor Perintah : ")


def validateData(x):
    # print("masuk function validate")
    potong = x.split("|")
    if len(potong) > 1:
        if(potong[0] != "" and potong[1] != 0 and potong[2] != 0 and potong[3] != 0):
            print("masuk function validate")
            return True


def getData(x):
    potong = x.split("|")
    if(validateData(x)):
        node = potong[0]
        detak = potong[1]
        oksigen = potong[2]
        suhu = potong[3]
        # print("masuk if getdata")
        # print(node)

    waktu = datetime.datetime.now()
    # print(localtime)
    waktu = waktu.strftime('%Y-%m-%d %H:%M:%S')
    # print("masuk function getData")
    # print("data = ")
    # print(node, detak, oksigen, suhu, waktu)
    return node, detak, oksigen, suhu, waktu


# jumlah threads(jumlah max req dari dari app)
POOL_SIZE = 5


def InsertDb(x):
    db = mysql.connector.connect(
        host='localhost',
        database='coba',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )

    print("masuk function insertDB")
    print(x)
    # cursor = db.cursor(buffered=True)
    # hasil get data dari arduino
    # node = x[0]
    # detak = x[1]
    # oksigen = x[2]
    # suhu = x[3]
    # waktu = [4]

    # convert data sebelum masuk ke db
    # node = str(node)
    # detak = int(detak)
    # oksigen = int(oksigen)
    # suhu = float(suhu)

    # queryInsert = (
    #     "INSERT INTO data (waktu, node, detak, oksigen, suhu) VALUES (%s, %s, %s, %s, %s)")
    # values = (waktu, node, detak, oksigen, suhu)

    # cursor.execute(queryInsert, values)

    # # commit data ke database
    # db.commit()

    # cursor.close()
    # db.close()


while appRunning:
    # while menuShow:
    #     print(" ")
    if(perintah == "2"):
        s.write(str.encode("a"))
        print("Pemeriksaan sedang dilakukan mohon tunggu...")
        print("Nama Node | detak Jantung | Oksigen | Suhu | Waktu ")
        while sensing:
            # ambil data sensing arduino
            msg = s.readline().decode("ascii").strip()
            print("hasil sensing ardu : ")
            print(msg)
            print("masuk while sensing")
            counter = counter + 1

            with concurrent.futures.ThreadPoolExecutor() as executor:
                if(counter > 5):
                    counter = 0
                    future = executor.submit(getData, msg)
                    if(future.done()):
                        print(future.result())
                        # print("data future" + str(data))
                        # print(len(data))
                        # if data != None:
                        #     f2 = executor.submit(InsertDb, future.result)
