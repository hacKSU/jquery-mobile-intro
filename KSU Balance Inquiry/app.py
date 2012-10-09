import urllib
import urllib2

from flask import Flask
app = Flask(__name__)

@app.route("/")
def start():
    # Root url for the account service
    root_url = 'https://services.jsatech.com'
    
    # STAGE 1
    url = root_url + '/login.php';
    values = {'cid':40,'save':1,'loginphrase':'cfullmer','password':'Gyroman1','x':1,'y':1}
    data = urllib.urlencode(values)
    req = urllib2.Request(url, data)
    response = urllib2.urlopen(req)
    the_page = response.read()
    key_index = the_page.rfind('window.location.href=')
    login_url = the_page[key_index + 22:key_index + 97]
    key_cid = the_page[key_index + 32:key_index + 78]
    key = the_page[key_index + 32:key_index + 70]
    print login_url
    
    #STAGE 2
    url = root_url + login_url;
    req = urllib2.Request(url, data)
    response = urllib2.urlopen(req)
    the_page = response.read()
    print the_page
    
    #STAGE 3
    url = root_url + '/login-check.php' + key;
    print url
    req = urllib2.Request(url, data)
    response = urllib2.urlopen(req)
    the_page = response.read()
    print the_page
    
    #STAGE 4
    url = root_url + '/index.php' + key_cid;
    req = urllib2.Request(url, data)
    response = urllib2.urlopen(req)
    the_page = response.read()
    print the_page
    print the_page[the_page.rfind('Current Balance:'):the_page.rfind('Current Balance:') + 28]
    
    url = root_url + '/logout.php' + key_cid;
    req = urllib2.Request(url, data)
    response = urllib2.urlopen(req)
    the_page = response.read()
    #print the_page
    
    return 'Hello World'

if __name__ == "__main__":
    app.run()
