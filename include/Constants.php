<?php

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "tibgo");


define('USER_CREATED', 101);
define('USER_EXISTS', 102);
define('USER_FAILURE', 103);


/* The Secret Key */
define('SECRET_KEY', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlcnJvciI6ZmFsc2UsIm1lc3NhZ2UiOiJVc2VyIGNyZWF0ZWQgc3VjY2Vzc2Z1bGx5IiwidXNlciI6eyJlcnJvciI6ZmFsc2UsIm1lc3NhZ2UiOiJpbnNlcnRlZCBTdWNjZXNzZnVsbHkgaW4gZG9jcyIsInVzZXIiOnsiaWQiOjExNywiZW1haWwiOiJodXNuYWluQGdtYWlsLmNvbW1tbSIsIm5hbWUiOiJIdXNuYWluIEFtaXIgIiwicGhvbmVOdW1iZXIiOiIrOTIzMjQ0NDAzNjM2IiwidXNlclR5cGUiOiJ1c2VyIiwiQURUIjoidXBkYXRlIHN1c3Nlc3NmdWxseSAyMjIiLCJhZGRyZXNzIjoiYXNkYXNkYXNkYSBzZGFzZGFzZGFkYWRhc2QiLCJ3b3JraW5nSG91cnMiOiJhc2Rhc2Rhc2QiLCJjaGFyZ2VQZXJWaXNpdCI6MTIzLCJsYXRMbmciOiJhc2Rhc2QiLCJpc1NwZWNpYWxpc3QiOjEsInNwZWNpYWxpc3RJbiI6ImFzZGFzZGFzZCIsImRvY1R5cGUiOiJhbGxvcGF0aHkiLCJkX2lkIjo3fX19.2JwMUOYCRuvs5PcoWhI4pOcA3_UnECiNfXOYO4qB_KI');

/* this can be the servername */
define('ISSUER_CLAIM','LOCAL_HOST');
define('HEADER_AUTHORIZATION','Authorization');




/* Data Types*/


/* 
HTTP Status Codes

2xx Success

200 OK
 
201 Created

202 Accepted

203 Non-Authoritative Information

204 No Content

205 Reset Content

206 Partial Content

207 Multi-Status (WebDAV)

208 Already Reported (WebDAV)

226 IM Used

3xx Redirection

300 Multiple Choices

301 Moved Permanently

302 Found

303 See Other

304 Not Modified

305 Use Proxy

306 (Unused)

307 Temporary Redirect

308 Permanent Redirect (experimental)

4xx Client Error

400 Bad Request

401 Unauthorized

402 Payment Required

403 Forbidden

404 Not Found

405 Method Not Allowed

406 Not Acceptable

407 Proxy Authentication Required

408 Request Timeout

409 Conflict

410 Gone

411 Length Required

412 Precondition Failed

413 Request Entity Too Large

414 Request-URI Too Long

415 Unsupported Media Type

416 Requested Range Not Satisfiable

417 Expectation Failed

418 I'm a teapot (RFC 2324)

420 Enhance Your Calm (Twitter)

422 Unprocessable Entity (WebDAV)

423 Locked (WebDAV)

424 Failed Dependency (WebDAV)

425 Reserved for WebDAV

426 Upgrade Required

428 Precondition Required

429 Too Many Requests

431 Request Header Fields Too Large

444 No Response (Nginx)

449 Retry With (Microsoft)

450 Blocked by Windows Parental Controls (Microsoft)

451 Unavailable For Legal Reasons

499 Client Closed Request (Nginx)
 
5xx Server Error

500 Internal Server Error

501 Not Implemented

502 Bad Gateway

503 Service Unavailable

504 Gateway Timeout

505 HTTP Version Not Supported

506 Variant Also Negotiates (Experimental)

507 Insufficient Storage (WebDAV)

508 Loop Detected (WebDAV)

509 Bandwidth Limit Exceeded (Apache)

510 Not Extended

511 Network Authentication Required

598 Network read timeout error

599 Network connect timeout error

CREATE TABLE doctors( 
    d_Id INT AUTO_INCREMENT PRIMARY KEY,
    address varchar(200) not null,
    openingTime varchar(10) not null,
    closingTime varchar(10) not null,
    chargePerVisit INT(100) not null,
    latLng varchar(100) not null,
    isSpecialist Boolean not null,
    specialistIn varchar(100),
    docType VARCHAR(50) not null,
	docImgUrl VARCHAR(200) not null,
    u_id INT NOT NULL,
    CONSTRAINT u_id FOREIGN KEY (u_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE )
https://github.com/Dawood-Amir/Tibgo-PHP-Api.git
INSERT INTO doctors (address,
                     openingTime,
                     closingTime,
                     chargePerVisit,
                     latLng,
                     isSpecialist,
                     specialistIn,
                     docType,
                     docImgUrl,
                     u_id)
        VALUES ('unknown',
                '10',
                '8',
                12000,
                '12.31231293,123123.12',
                true,
                'Skin Specialist',
                'alopathic',
                'asdasdasdasdasdafafsdvsdsdfgsd',
                1);
       

{
    "error": false,
    "message": "Found doctor with that id",
    "doctor": {
        "id": 4,
        "email": "dawood@docto.com",
        "name": "Dawood Amir",
        "phoneNumber": "+923244403636",
        "userType": "doctor",
        "ADT": "updatedw",
        "address": "asdasdasda sdasdasdadadasd",
        "openingTime": "8",
        "closingTime": "10",
        "docImgUrl": "asdbvasdvasd hjvdasgdhjsvdjvashjdf hjasvdhdga",
        "chargePerVisit": 123,
        "latLng": "asdasd",
        "isSpecialist": 1,
        "docType": "allopathy",
        "d_id": 2
    }
}



 */
