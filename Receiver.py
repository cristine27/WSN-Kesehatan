# Base-station Code
from mysql.connector import Error
import mysql.connector  # connect python dengan mysql
import time
import serial  # akses serial port
import datetime  # waktu
import concurrent.futures  # threads
import threading

# variabel
appRunning = True
menuShow = True
sensing = True
counter = 0
insertDataPasien = True
idPasien = 0

# dictionary untuk mapping pasien dengan node yang digunakan 
# misal node1 : pasienX
# dictionary key akan di masukkan terlebih dahulu 
Pasien = {
    
}

# dictionary untuk mapping nama node dengan id node
# misal node1 : 1
idNode = {

}

# dictionary untuk simpan status node online atau offline
# bisa jadi uda ga butuh, krna bisa pake db node lgsg select
Node = {

}
global statusNode



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
#print("1. Check status Node")
print("1. Mulai Pemeriksaan")
print("2. Mematikan Node")
print("3. Menghidupkan Node")
print("3. Keluar dari Aplikasi")
print("----------------------")
print("Silahkan Input Nomor Perintah : ")

perintah = input()
# print(perintah)


def mainMenu():
    print("Aplikasi Base Station Berjalan")
    print("----------------------")
    print("Daftar Menu Perintah : ")
    #print("1. Check status Node")
    print("1. Mulai Pemeriksaan")
    print("2. Mematikan Node")
    print("3. Menghidupkan Node")
    print("3. Keluar dari Aplikasi")
    print("----------------------")
    print("Silahkan Input Nomor Perintah : ")
    print("")
    
def mapNodeName():
    db = mysql.connector.connect(
            host='localhost',
            database='WSN',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
    )
    
    cursor = db.cursor(buffered=True)
    
    cursor.execute("SELECT namaNode,idNode from node")
    
    res = cursor.fetchall()
    
    for x in res:
        nama = x[0]
        temp = x[1]
        if nama not in Pasien.keys() and nama not in Node.keys() and nama not in idNode.keys():
            Pasien[nama] = 0
            Node[nama] = "offline"
            idNode[nama] = temp

    cursor.close()
    db.close()

    # testing isi dictionary
    #print(Pasien)
    #print(Node)
    #print(idNode)

def validateData(x):
    # print("masuk function validate")
    res = False
    potong = x.split("|")
    #isi data = namaNode | detak | oksigen | temperatur | status
    if len(potong) > 1:
        if(potong[0] != "" and potong[1] != 0 and potong[2] != 0 and potong[3] != 0 and potong[4] != -1):
            # print("masuk function validate")
            res = True
    return res

def getDataSense(x):
    potong = x.split("|")
    if validateData(x):
        node = potong[0]
        detak = potong[1]
        oksigen = potong[2]
        suhu = potong[3]
        statusNode = potong[4]
        # print("masuk if getdata")
        # print(node)

    waktu = datetime.datetime.now()
    waktu = waktu.strftime('%Y-%m-%d %H:%M:%S')
    # print(localtime)
    
    # print("masuk function getData")
    # print("data = ")
    # print(node, detak, oksigen, suhu, waktu)
    return node, detak, oksigen, suhu, waktu

def konekDb():
    # connect to mysql database
    try:
        db = mysql.connector.connect(
            host='localhost',
            database='WSN',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
        )

        if db.is_connected():
            print("connected to MySQL database")

    except Error as e:
        print(e)

def hidupkanNode(namaNode):
    db = mysql.connector.connect(
        host='localhost',
        database='WSN',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )

    cursor = db.cursor(buffered=True)
    queryUpdate = "UPDATE node SET status = 1 WHERE namaNode = %s"
    val = (namaNode,)

    cursor.execute(queryUpdate, val)

    db.commit()
    cursor.close()
    db.close()

def matikanNode(namaNode):
    db = mysql.connector.connect(
        host='localhost',
        database='WSN',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )
    temp = 0
    cursor = db.cursor(buffered=True)
    queryUpdate = "UPDATE node SET status = 0 WHERE namaNode = %s"
    val = (namaNode,)

    cursor.execute(queryUpdate, val)

    db.commit()
    cursor.close()
    db.close()


def counterStart():
    global statusNode
    enter = "try : "

    if counter > 10:
        statusNode = False

    return enter


def resetCounter():
    global counter
    counter = 0

def InsertDb(x):
    # konekDb()
    db = mysql.connector.connect(
        host='localhost',
        database='WSN',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )

    cursor = db.cursor(buffered=True)
    # hasil get data dari arduino
    node = str(x[0])
    detak = str(x[1])
    oksigen = str(x[2])
    suhu = str(x[3])
    waktu = x[4]
    
    # convert data sebelum masuk ke db
    node = str(node)
    
    idPasien = "".join(map(str, Pasien.get(node,None)))
    idNode = "".join(map(str, Node.get(node,None)))

    #convert data
    detak = str(detak)
    oksigen = str(oksigen)
    suhu = str(suhu)
    
    queryInsert = (
        "INSERT INTO periksa (idPasien, idNode, waktu, hasil1, hasil2, hasil3)"
        "VALUES (%s, %s, %s, %s, %s, %s)"
    )

    values = (idPasien, idNode, waktu, detak, oksigen, suhu)
    # print(idPasien,idNode,waktu,detak,oksigen,suhu)
    
    #commit query sql
    cursor.execute(queryInsert, values)
    # print("execute query")
    db.commit()

    cursor.close()
    db.close()
    
def verifyidPasien(idPasien):
    db = mysql.connector.connect(
        host='localhost',
        database='WSN',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )
    isValid = False
    cursor = db.cursor(buffered=True)
    
    cursor.execute("Select idPasien FROM pasien")
    
    res = cursor.fetchall()
    
    for x in res:
        temp = "".join(map(str,x))
        if temp == idPasien:
            isValid = True
    
    return isValid

def verifyidNode(namaNode):
    db = mysql.connector.connect(
        host='localhost',
        database='WSN',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )
    isValid = True
    cursor = db.cursor(buffered=True)
    
    cursor.execute("Select idNode FROM node WHERE namaNode=namaNode")
    
    res = cursor.fetchall()
    
    for x in res:
        temp = "".join(map(str,x))
        if temp == 0:
            isValid = False
        else:
            Node[namaNode] = temp
    
    return isValid

def insertDataNodePasien(x):
    potong = x.split(",")
    if(len(potong)>0):
        idP = potong[0]
        Node = potong[1]
        if(idP!=0 and Node!=""):
            if(verifyidPasien(idP) and verifyidNode(Node)):
                global idPasien 
                idPasien = idP

                if checkStatusNode(Node):
                    # masukan idPasien ke dalam dictionary dengan key NamaNode
                    Pasien[Node] = idP
                    print(Pasien)
                    print("Assign Pasien pada Node Berhasil")
                    print("")
                else:
                    global insertDataPasien
                    insertDataPasien = False
                    print("Maaf saat ini node sedang offline")
                    print("")
                    print("Silahkan menghidupkan node terlebih dahulu")
                    mainMenu()
            else:
                check = False
                print("Maaf idNode dan idPasien yang dimasukkan tidak ditemukan")
                print("Silahkan ulangi atau check data kembali)")
                global insertDataPasien
                insertDataPasien = False
                mainMenu()

def checkIfAttached(x):
    check = False
    potong = x.split("|")
    print(potong)
    if(len(potong)>0):
        if(potong[4]=='0'):
            check = True
    return check
                
def checkStatusNode(namaNode):
    db = mysql.connector.connect(
        host='localhost',
        database='WSN',
        user='phpmyadmin',
        password='raspberry',
        pool_name='mypool',
        pool_size=POOL_SIZE+1
    )

    cursor = db.cursor(buffered=True)
    
    cursor.execute("Select status FROM node WHERE namaNode=namaNode")
    isValid = True
    res = cursor.fetchall()
    
    for x in res:
        temp = "".join(map(str,x))
        if temp == '0':
            isValid = False

    return isValid
            
    

# jumlah threads(jumlah max req dari dari app)
POOL_SIZE = 20

while appRunning:
    mapNodeName()
    while menuShow:
        print(" ")
        if(perintah == "1"):
            s.write(str.encode("a"))
            mapNodeName()
            print("Silahkan Masukkan Jumlah Pasien yang Akan di Periksa: ")
            print("")
            jumlahPasien = int(input())
            
            while(jumlahPasien>0):
                print("Silahkan Masukkan idPasien yang akan di Periksa oleh Tiap Node: ")
                print("Format Penulisan : idPasien1,namaNode")
                print("Penulisan dilakukan persatu pasien")
                print("")
                formatPasien = input()
                jumlahPasien = jumlahPasien - 1
                insertDataNodePasien(formatPasien)
            if insertDataPasien:
                print("Pemeriksaan sedang dilakukan mohon tunggu...")
                #print("Nama Node | detak Jantung | Oksigen | Suhu | Waktu ")
                while sensing and counter<20:
                    # ambil data sensing arduino
                    #msg = s.readline().decode("ascii").strip()
                    counterStart()
                    counter = counter + 1
                    time.sleep(5)
                    with concurrent.futures.ThreadPoolExecutor(max_workers=5) as executor:
                        msg = s.readline().decode("ascii").strip()
                        #check apakah alat terpasang dengan benar
                        time.sleep(5)
                        status = checkIfAttached(msg)
                        if status:
                            break
                        else:
                            time.sleep(1)
                            future = executor.submit(getDataSense, msg)
                            time.sleep(1)
                            data = future.result()

                            if future.done() and data != None:
                                future2 = executor.submit(InsertDb, data)
                
                if status:
                    print("Perangkat Tidak Tersambung, Silahkan Periksa Kembali Perangkat..")
                    mainMenu()
                if counter==20:
                    resetCounter()
                    mainMenu()

        # turn off status node
        elif perintah == "2":
            s.write(str.encode("c").strip())
            flag = False
            
            print("Silahkan masukkan jumlah node yang akan dimatikan :")
            jumlahNode = int(input())
            
            print("Silahkan masukkan nama node yang akan dimatikan : ")
            print("format penulisan : namaNode1,namaNode2")
            namaNode = input()

            potong = namaNode.split(",")
            while jumlahNode>0:
                if(verifyidNode(potong[jumlahNode-1])):
                    flag = True
                    with concurrent.futures.ThreadPoolExecutor() as executor:
                        future = executor.submit(matikanNode(potong[jumlahNode-1]))
                        jumlahNode = jumlahNode - 1
                else:
                    break
            if flag:
                print("Node berhasil dimatikan")
            else:
                print("Nama node tidak terdaftar")
                print("Silahkan cek kembali nama node masukan")
            mainMenu()
            
        # turn on status node
        elif perintah == "3":
            s.write(str.encode("c").strip())
            flag = False
            
            print("Silahkan masukkan jumlah node yang akan dinyalakan :")
            jumlahNode = int(input())
            
            print("Silahkan masukkan nama node yang akan dinyalakan : ")
            print("format penulisan : namaNode1,namaNode2")
            namaNode = input()

            potong = namaNode.split(",")
        
            while jumlahNode>0:
                if(verifyidNode(potong[jumlahNode-1])):
                    flag = True
                    with concurrent.futures.ThreadPoolExecutor() as executor:
                        future = executor.submit(hidupkanNode(potong[jumlahNode-1]))
                        jumlahNode = jumlahNode - 1
                else:
                    break
            if flag:
                print("Node berhasil dinyalakan")
            else:
                print("Nama node tidak terdaftar")
                print("Silahkan cek kembali nama node masukan")
            mainMenu()

        # turn off basestation
        elif perintah == "4":
             s.write(str.encode("c").strip())
             print("Mematikan Program Base Statsion")
             # os.system("Receiver.py")
             print("================================")
             print("Sensing Telah Dihentikan")
             print("Base Station Offline")
             exit()

        else:
            print("Input Perintah Salah")
            print("Restart Aplikasi")
            exit()

        perintah = input()


# def updateStatusSensing(dataSensing):
#     db = mysql.connector.connect(
#         host='localhost',
#         database='WSN',
#         user='phpmyadmin',
#         password='raspberry',
#         pool_name='mypool',
#         pool_size=POOL_SIZE+1
#     )

#     waktu = datetime.datetime.now()
#     waktu = waktu.strftime('%Y-%m-%d %H:%M:%S')
#     TempStatus = 0

#     # cursor = db.cursor(buffered=True)

#     # queryNode2 = ("UPDATE nodesensor SET status")

# def goingOffline(namaNode):
#     db = mysql.connector.connect(
#         host='localhost',
#         database='WSN',
#         user='phpmyadmin',
#         password='raspberry',
#         pool_name='mypool',
#         pool_size=POOL_SIZE+1
#     )

#     waktu = datetime.datetime.now()
#     waktu = waktu.strftime('%Y-%m-%d %H:%M:%S')
#     TempStatus = 0

#     cursor = db.cursor(buffered=True)

#     queryUpdate = ("UPDATE node SET status = %s, waktu = %s WHERE namaNode == %s")
#     valueUpdate = (TempStatus, waktu, namaNode)

#     cursor.execute(queryUpdate,valueUpdate)

#     db.commit()
#     cursor.close()
#     db.close()

# def getPingNode(x):
#     potong = x.split("|")
#     temp = ""
#     if validateData(x):
#         node = potong[0]
#         status = potong[4]

#     if(str(status)=="1"):
#         temp = "online"
#     else:
#         temp = "offline"
        
#     if(str(node)=="node1"):
#         Node["node1"] = temp
#     else:
#         Node["node2"] = temp
    
#     return node, status

# elif perintah == "0":
#             print("Mengirim perintah check status")
#             print("Respon akan diberikan dalam beberapa saat, mohon menunggu.")

#             s.write(str.encode("b"))
#             #msg = s.readline().decode("ascii").strip()
#             #print(msg)
#             time.sleep(5)
#             respon = 0
#             while(counter < 20):
#                 #respon = 0
#                 with concurrent.futures.ThreadPoolExecutor(max_workers=1) as executor:
#                     counterStart()
#                     msg = s.readline().decode("ascii").strip()
#                     counter += 1
#                     time.sleep(1)
#                     future3 = executor.submit(getPingNode, msg)
#                     time.sleep(1)
#                     if future3.done() and future3.result() != None:
#                         respon = 1
#                         print("")
#                         time.sleep(1)
#                         print("Hasil Check Status Node")
#                         future4 = executor.submit(updateNodeStatus, future3.result())
#                         global statusNode
#                         statusNode = True
#                         print(future3.result())
#             if respon == 1:
#                 print(" ")
#                 print(Node)
#                 print("Check Node Selesai")
#                 print(" ")
#             elif(respon==0 and bool(statusNode)==False):
#                 print(" ")
#                 print(Node)
#                 print("Node Tidak Merespon")
#                 print("Silahkan Cek Perangkat")
#                 print(" ")
#             resetCounter()
#             respon = 0
#             mainMenu()