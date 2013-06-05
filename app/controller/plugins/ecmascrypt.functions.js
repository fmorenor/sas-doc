//var plainbox = null;
//var encryptedbox = null;
var moobox = ecmaScrypt.modeOfOperation.CBC;
var passphrase = "ghdjr84kd94j678oe3OPd3K3754NdHY3";
var keysizebox = 16;
var keybox = "db5d3dbe70893c83c82d7c2601d8ae7d";
var ivbox = "a5c71cb8e5273b291e8bf59a7e1ae1df6bb5976f4c56d9b28c5464cd98af1e16";
var origLen = null;

function runEncrypt(plainbox)
{
    var ciph = ecmaScrypt.encrypt(plainbox,parseInt(moobox),ecmaScrypt.toNumbers(keybox),parseInt(keysizebox),ecmaScrypt.toNumbers(ivbox));
    
    if(ciph)
    {
        var outhex = '';
        for(var i = 0;i < ciph.cipher.length;i++)
        {
            outhex += ecmaScrypt.toHex(ciph.cipher.charCodeAt(i));
        }
        
        encryptedbox = outhex;
        origLen = ciph.originalsize;
    }
    return encryptedbox;
}

function runDecrypt(encryptedbox, origLen)
{    
    var innumbers = ecmaScrypt.toNumbers(encryptedbox);
    var instring = '';
    for(var i = 0;i < innumbers.length; i++)
    {
        instring += String.fromCharCode(innumbers[i])
    }
    var dec = '';
    try
    {
        dec = ecmaScrypt.decrypt(instring,parseInt(origLen),parseInt(moobox),ecmaScrypt.toNumbers(keybox),parseInt(keysizebox),ecmaScrypt.toNumbers(ivbox));
    }
    catch(e)
    {
        console.log('Error during decryption.\n\nDid you use the correct Pass Phrase/Hex Key, Hex IV, Mode of Operation, and Original String Length (CBC only)?');
    }
    plainbox = dec;
    return(plainbox);
}
