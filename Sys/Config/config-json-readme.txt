{
# currently modules doesn't load services but loads all other subfoldered files in Sys directory
    "storeone" : {"modules" : ["catalog", "sessions", "caching", "services"]},
    "services" : {
        "sessionService" : {"type" : "soap", "url" : "http://localhost:8080/SBSessions/SBSessions?WSDL", "modules" : ["sessions"], "credentials" : ""},
        "storeService" : {"type" : "soap", "url" : "http://localhost:8080/SBSessions/SBStore?WSDL", "modules" : [""], "credentials" : ""}
        },
# caching can be file or memcache, location is a filepath or a url to memcache
    "caching" : {"type" : "file", "location" : "C:\pathToFileCache"},
# sessions can be saved to file, php default or service endpoint
    "sessions" : {"type" : "file" },
# not much to be said about catalog at the moment. It's a bit of an anomoly since it's the only store model rooted in the sys ini
    "catalog" :  { }
}