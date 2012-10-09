import urllib
import urllib2
import ClientCookie

from flask import Flask
app = Flask(__name__)

@app.route("/")
def start():
    # Root url for the account service
    root_url = 'https://keys.kent.edu:44220/ePROD'
    
    # Retrieve the login page.
    url = root_url + '/twbkwbis.P_WWWLogin'
    req = urllib2.Request(url)
    response = ClientCookie.urlopen(req)
    the_page = response.read()
    
    # Login into the service.
    url = root_url + '/twbkwbis.P_ValLogin'
    values = {'sid':'cfullmer','PIN':'Gyroman1'}
    data = urllib.urlencode(values)
    req = urllib2.Request(url, data)  
    response = ClientCookie.urlopen(req)
    the_page = response.read()
    
    # Get the HTML for the schedule page.
    url = root_url + '/bwskfshd.P_CrseSchdDetl'
    values = {'term_in':'201280'}
    data = urllib.urlencode(values)
    req = urllib2.Request(url, data)
    response = ClientCookie.urlopen(req)
    the_page = response.read()
    print the_page    

    return 'Hello World'

if __name__ == "__main__":
    app.run()
