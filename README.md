# olsera

* create database dengan nama olsera atau dengan import database olsera.sql

* untuk file laravel nya terdapat pada branch master

* pada saat put data :

{
    "data" : {
        "id" : 11,
        "nama": "Baju Anak"
    },
    "pajak" : [
        {
            "id": 20, //Jika 0 maka insert, jika tidak 0 maka update data pajak
            "item_id": 12,
            "nama": "Pajak",
            "rate": "0.12",
            "deleteflag" : 0 //Jika 1 maka delete data pajak
        },
        {
            "id": 21,
            "item_id": 12,
            "nama": "Pajak Online",
            "rate": "0.10",
            "deleteflag" : 1
        },
        {
            "id": 0,
            "item_id": 11,
            "nama": "PPh",
            "rate": "0.03",
            "deleteflag" : 0
        }
    ]
}
