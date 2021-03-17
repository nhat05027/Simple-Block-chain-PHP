import hashlib

def hashstr(stri):
    result = hashlib.sha256(stri.encode()).hexdigest()
    return result
def mine(stri, diff):
    i = 0
    hashstring = ""
    x = "0" * int(diff)
    while hashstring[0:int(diff)] != x:
        a = stri + str(i)
        hashstring = hashstr(a)
        print(i);
        i = i+1
    return i;



if __name__ == '__main__':
    s = input("Nhập string vào: \n")
    n = input("Độ khó: \n")
    t = mine(s, n) - 1
    print("Key: ",t)
    s = s + str(t)
    print("Hash: ",hashstr(s))
    input("Nhấn enter để tắt \n")


