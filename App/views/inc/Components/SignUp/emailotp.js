function sendOtp() {
    const email = document.getElementById('email').value;
    const otpverify = document.getElementById('otpverify ')[0];

    Email.send({
        SecureToken : "C973D7AD-F097-4B95-91F4-40ABC5567812",
        To : 'them@website.com',
        From : "you@isp.com",
        Subject : "This is the subject",
        Body : "And this is the body"
    }).then(
    message => alert(message)
    );
}