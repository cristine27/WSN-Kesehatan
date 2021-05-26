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
counter = 15
insertDataPasien = True
idPasien = 0

# dictionary untuk mapping pasien dengan node yang digunakan 
# misal node1 : pasienX
# dictionary key akan di masukkan terlebih dahulu 
Pasien = {
    
}

# dictionary untuk mapping status pasien,node apakah valid
# 1 = valid, 2 = node offline, 3 = id tidak ditemukan
# misal 
# [0] = 1
StatusInput = {

}

# dictionary untuk simpan status node online atau offline
# bisa jadi uda ga butuh, krna bisa pake db node lgsg select
Node = {

}

Parameter = {

}

idN = {

}

MapNodeParam = {

}

global hasilError

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
print("2. Check status Node")
print("3. Daftar Node baru")
print("4. Daftar Parameter pemantauan baru")
print("5. Assign Parameter ke Node")
print("6. Keluar dari Aplikasi")
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
    print("2. Check status Node")
    print("3. Daftar Node baru")
    print("4. Daftar Parameter pemantauan baru")
    print("5. Assign Parameter ke Node")
    print("6. Keluar dari Aplikasi")
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
        if nama not in Pasien.keys() and nama not in Node.keys() and nama not in idN.keys():
            Pasien[nama] = 0
            Node[nama] = "offline"
            idN[nama] = temp

    cursor.execute("SELECT idParameter,namaParameter from parameter")

    res = cursor.fetchall()

    for x in res: 
        idParameter = x[0]
        namaParameter = x[1]
        namaParameter.lower()
        if namaParameter not in Parameter.keys():
            Parameter[namaParameter] = idParameter

    sql = "SELECT node.namaNode as nama,parameter.namaParameter from memiliki JOIN node ON memiliki.idNode = node.idNode Join parameter ON memiliki.idParameter = parameter.idParameter"
    cursor.execute(sql)

    res = cursor.fetchall()

    for x in res:
        namaNode = x[0]
        namaParam = x[1]
        if namaNode not in MapNodeParam.keys():
            MapNodeParam[namaNode] = namaParam
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
    
    cursor = db.cursor(buffered=True)
    queryUpdate = "UPDATE node SET status = 0 WHERE namaNode = %s"
    val = (namaNode,)

    cursor.execute(queryUpdate, val)

    db.commit()
    cursor.close()
    db.close()

def getStatusNode(data):  
    status = False
    if validateData(data):
        potong = data.split("|")
        if(verifyidNode(potong[0])):
            Node[potong[0]] = "online"
            hidupkanNode(potong[0])
            status = True
    print(Node)
    return status

def counterStart():
    global counter

    while True:
        counter = counter - 1

        if counter == 0:
            resetCounter()
            break

def resetCounter():
    global counter
    counter = 15

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
    
    query = "Select idPasien FROM pasien where idPasien=%s"
    value = (idPasien,)
    cursor.execute(query,value)
    
    res = cursor.fetchall()
    
    for x in res:
        temp = "".join(map(str,x))
        if temp == idPasien:
            isValid = True
    
    return isValid

def verifyidNode(namaNode):
    # db = mysql.connector.connect(
    #     host='localhost',
    #     database='WSN',
    #     user='phpmyadmin',
    #     password='raspberry',
    #     pool_name='mypool',
    #     pool_size=POOL_SIZE+1
    # )
    isValid = True
    # cursor = db.cursor(buffered=True)
    # query = "Select idNode FROM node WHERE namaNode=%s"
    # value = (namaNode,)
    # cursor.execute(query, value)
    
    # res = cursor.fetchall()
    
    # for x in res:
    #     temp = "".join(map(str,x))
    #     if temp == 0 or temp == "0":
    #         isValid = False
    #     else:
    #         Node[namaNode] = temp

    if idN.get(namaNode)==None:
        isValid = False
    
    return isValid

def insertDataNodePasien(data,jumlahPasien):
    global hasilError
    pisah = data.split("|")
    i = 0
    while(i<jumlahPasien):
        temp = True
        potong = pisah[i].split(",")
        if(len(potong)>0):
            idP = potong[0]
            namaNode = potong[1]
            if(idP!=0 and Node!=""):
                if(verifyidPasien(idP) and verifyidNode(namaNode)):
                    temp = Node.get(namaNode)
                    if temp =="online" or temp == "1":
                        # masukan idPasien ke dalam dictionary dengan key NamaNode
                        Pasien[namaNode] = idP
                        StatusInput[i] = 1
                    else:
                        StatusInput[namaNode] = 2
                else:
                    StatusInput[i] = 3
                    hasilError = idP + "," + namaNode

        i = i + 1

def checkIfAttached(x):
    check = False
    potong = x.split("|")
    if(len(potong)>0):
        if(potong[4]=='0'):
            check = True
    return check

def insertNode(namaNode):
    flag = True
    if not verifyidNode(namaNode):
        flag = False
        print("Maaf silahkan input nama node lain")
        print("Nama node tidak boleh duplikat")
    else:
        db = mysql.connector.connect(
            host='localhost',
            database='WSN',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
        )

        cursor = db.cursor(buffered=True)

        #convert data
        
        queryInsert = (
            "INSERT INTO node (namaNode, status)"
            "VALUES (%s, %s)"
        )

        values = (namaNode,1)
        
        #commit query sql
        cursor.execute(queryInsert, values)
        # print("execute query")
        db.commit()

        cursor.close()
        db.close()
        print("Selamat Node baru berhasil ditambahkan..")
    return flag

def insertParameter(namaParameter):
    namaParameter.lower()
    flag = True
    
    if Parameter.get(namaParameter)!="":
        db = mysql.connector.connect(
            host='localhost',
            database='WSN',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
        )

        cursor = db.cursor(buffered=True)

        #convert data
        
        queryInsert = (
            "INSERT INTO parameter (namaParameter)"
            "VALUES (%s)"
        )

        values = (namaParameter,)
        
        #commit query sql
        cursor.execute(queryInsert, values)
        # print("execute query")
        db.commit()

        cursor.close()
        db.close()
        print("Selamat parameter baru berhasil ditambahkan..")
    else:  
        flag = False
        print("Maaf parameter telah tersedia")
    return flag

def assignNodeParam(namaNode,param):
    flag = True
    isParamEx = False
    db = mysql.connector.connect(
            host='localhost',
            database='WSN',
            user='phpmyadmin',
            password='raspberry',
            pool_name='mypool',
            pool_size=POOL_SIZE+1
        )

    cursor = db.cursor(buffered=True)
    print(verifyidNode(namaNode))
    print(Node.get(namaNode))
    if verifyidNode(namaNode):
        idNode = Node.get(namaNode)
        print(Parameter.get(param))
        for key,value in MapNodeParam.items():
            if key == namaNode and value == param:
                isParamEx = True
        if ((Parameter.get(param)!="None") and (not isParamEx)):
            idParam = Parameter.get(param)

            queryInsert = (
            "INSERT INTO memiliki (idNode, idParameter)"
            "VALUES (%s, %s)"
            )

            values = (idNode,idParam)
        
            #commit query sql
            cursor.execute(queryInsert, values)
            
            db.commit()
            cursor.close()
            db.close()
            print("Selamat assign parameter ke node berhasil..")
        else:
            flag = False
            if isParamEx:
                print("Maaf parameter sudah terdaftar pada node")
            else:
                print("Maaf parameter belum terdaftar mohon daftar terlebih dahulu")
            mainMenu()
    else:
        flag = False
        print("Maaf nama node yang dimasukkan tidak terdaftar")
    return flag
        

# jumlah threads(jumlah max req dari dari app)
POOL_SIZE = 20

while appRunning:
    while menuShow: 
        mapNodeName()
        temp = 0
        while True:
            temp = temp + 1
            msg = s.readline().decode("ascii").strip()
            getStatusNode(msg)
            if temp == 8:
                break
        print(" ")
        if(perintah == "1"):
            msg = s.readline().decode("ascii").strip()
            counter = 0
            print("Silahkan Masukkan Jumlah Pasien yang Akan di Periksa: ")
            print("")
            jumlahPasien = int(input())
            # while(jumlahPasien>0):
            print("Silahkan Masukkan idPasien yang akan di Periksa oleh Tiap Node: ")
            print("Format Penulisan : idPasien1,namaNode|idPasien2,namaNode|idPasien3,namaNode|")
            print("")
            formatPasien = input()
            # jumlahPasien = jumlahPasien - 1
            insertDataNodePasien(formatPasien,jumlahPasien)
            print(StatusInput)
            print("Pemeriksaan sedang dilakukan mohon tunggu...")
            flag = False
            for key,value in StatusInput.items():
                if value == 2:
                    print("Maaf saat ini ", key, " sedang offline")
                    print("")
                elif value == 3:
                    hasilError = hasilError.split(',')
                    print("Maaf " + hasilError[0] + " dan " + hasilError[1] + " yang dimasukkan tidak ditemukan")
                    print("Silahkan ulangi atau check data kembali)")
                else:
                    flag = True
            if flag:
                    print("Assign Pasien pada Node Berhasil")
                    print(Pasien)
            #print("Nama Node | detak Jantung | Oksigen | Suhu | Waktu ")
            while sensing and counter<15:
                # ambil data sensing arduino
                msg = s.readline().decode("ascii").strip()
                counter = counter + 1
                time.sleep(5)
                with concurrent.futures.ThreadPoolExecutor(max_workers=10) as executor:
                    #check apakah alat terpasang dengan benar
                    time.sleep(5)
                    status = checkIfAttached(msg)
                    if status==False:
                        time.sleep(1)
                        future1 = executor.submit(getDataSense, msg)
                        # future2 = executor.submit(getDataSense, msg)
                        time.sleep(1)
                        data1 = future1.result()
                        # data2 = future2.result()

                        if future1.done() and data1 != None:
                            future3 = executor.submit(InsertDb, data1)
                        # if future2.done() and data2 != None:
                        #     future4 = executor.submit(InsertDb, data2)
                        
                    elif status==True:
                        print("Sensor Tidak Terpasang dengan Baik, Silahkan Periksa Kembali Perangkat..")
                if counter==15:
                    counter = 0
                    mainMenu()

        elif perintah == "2":
            print("Mohon menunggu..")
            # temp = 0
            # while True:
            #     temp = temp + 1
            #     msg = s.readline().decode("ascii").strip()
            #     getDataSense(msg)
            #     if temp == 15:
            #         break

            for key,value in Node.items():
                print(key, ' : ', value)
            mainMenu()

        # turn off status node
        elif perintah == "8":
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
        elif perintah == "7":
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

        elif perintah == "3":
            print("Silahkan input namaNode : ")
            namaNode = input()
            namaNode.lower()
            flag = insertNode(namaNode)
            if flag:
                print("Apakah node ingin diassign ke parameter pemantauan ?")
                print("Ketik 1 jika ingin melanjutkan dan 0 jika tidak")
                masukkan = int(input())
                if masukkan == 1:
                    print("Satu node hanya dapat memiliki 3 parameter")
                    print("Berapa parameter yang ingin anda assign ? ")
                    jumlahParam = int(input())
                    if jumlahParam<3:
                        print("Maaf parameter hanya dapat 3")
                        mainMenu()
                    elif jumlahParam>0 and jumlahParam<=3:
                        while(jumlahParam>0):
                            print("Silahkan input nama parameter : ")
                            namaParameter = input()
                            namaParameter.lower()
                            assignNodeParam(namaNode,namaParameter)
                            jumlahParam = jumlahParam - 1
            else:
                mainMenu()

        elif perintah == "4":
            print("Silahkan input nama parameter : ")
            namaParameter = input()

            insertParameter(namaParameter)
            mainMenu()

        elif perintah == "5":
            print("Silahkan input nama node : ")
            namaNode = input()
            namaNode.lower()

            print("Satu node hanya dapat memiliki 3 parameter")
            print("Berapa parameter yang ingin anda assign ? ")
            jumlahParam = int(input())
            if jumlahParam>3:
                print("Maaf parameter hanya dapat 3")
                mainMenu()
            elif jumlahParam>0 and jumlahParam<=3:
                while(jumlahParam>0):
                    print("Silahkan input nama parameter : ")
                    namaParameter = input()
                    namaParameter.lower()
                    assignNodeParam(namaNode,namaParameter)
                    jumlahParam = jumlahParam - 1
            mainMenu()

        # turn off basestation
        elif perintah == "6":
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