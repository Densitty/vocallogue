// forgot password form
const forgotPassword = document.getElementById("forgotpasswordform");
const forgotEmail = document.getElementById("forgotemail");
const forgotPasswordMessage = document.getElementById("forgotpasswordmessage");

forgotPassword.addEventListener("submit", (e) => {
  e.preventDefault();

  const request = new XMLHttpRequest();

  request.addEventListener("load", () => {
    let response = null;

    if (request.readyState === 4 && request.status === 200) {
      console.log(request);
      let responseObject = null;

      // get response from the server
      responseObject = JSON.parse(request.responseText);

      if (responseObject) {
        if (responseObject.ok) {
          // redirect user to the mainpage
          // window.location = "./mainpage.php";
          console.log("Mail sent");
          clearOutputMessage(forgotPasswordMessage);

          displayResponseObjectMessage(
            responseObject,
            forgotPasswordMessage,
            "#2a641b"
          );
        } else {
          clearOutputMessage(forgotPasswordMessage);

          displayResponseObjectMessage(
            responseObject,
            forgotPasswordMessage,
            "#fe0001"
          );
        }
      }
    } else {
      console.log("Could not parse JSON!");
      forgotPasswordMessage.innerHTML = `
      <div class="alert alert-danger">An error occured. Please try again later.</div>
      `;
    }
  });

  // get the data from the form and send to server
  const dataToPost = `forgotEmail=${forgotEmail.value}`;

  request.open("POST", "./forgot_password.php");

  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.send(dataToPost);
});

// make a request to submit the sign-up form
const signup = document.getElementById("signupform");

// get the signupmessage div
const signupmessage = document.getElementById("signupmessage");

const rememberme = document.getElementById("rememberme");
let remember_me = false;

rememberme.addEventListener("change", (e) => {
  remember_me = !remember_me;
  console.log(remember_me);
});

signup.addEventListener("submit", (e) => {
  // prevent default behaviour
  e.preventDefault();
  const username = signup.querySelector("#username");
  const email = signup.querySelector("#email");
  const password = signup.querySelector("#password");
  const password2 = signup.querySelector("#password2");

  const request = new XMLHttpRequest();

  request.addEventListener("load", () => {
    let response = null;

    if (request.readyState === 4 && request.status === 200) {
      console.log(request);
      response = JSON.parse(request.responseText);
      responseHandler(response);
    } else {
      console.log("Could not parse JSON!");
      signupmessage.innerHTML = `
      <div class="alert alert-danger">An error occured. Please try again later.</div>
      `;
    }
  });

  // get the data from the form and send to server
  const dataToPost = `username=${username.value}&email=${email.value}&password=${password.value}&password2=${password2.value}`;

  request.open("POST", "./signup.php");

  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.send(dataToPost);
});

function responseHandler(responseObject) {
  console.log(responseObject);
  // if response 4rm server is ok
  if (responseObject.ok) {
    // clear out message, if any, on the target element
    clearOutputMessage(signupmessage);

    // now display the messages from server in DOM
    displayResponseObjectMessage(responseObject, signupmessage, "#2c0faa");
  } else {
    // target the response message display in DOM and empty it
    clearOutputMessage(signupmessage);

    // now display the messages from server in DOM
    displayResponseObjectMessage(responseObject, signupmessage, "#fe0001");
  }
}

// the login dom elements
const login = document.getElementById("loginform");
const loginmessage = document.getElementById("loginmessage");

login.addEventListener("submit", (e) => {
  e.preventDefault();

  console.log(e);
  const email = e.target["1"];
  const password = e.target["2"];
  let rememberme = false;

  // console.log(e.target["3"]);
  e.target["3"].addEventListener("change", (e) => {
    rememberme = !rememberme;
  });

  const request = new XMLHttpRequest();

  request.addEventListener("load", () => {
    let response = null;

    if (request.readyState === 4 && request.status === 200) {
      console.log(request);
      let responseObject = null;

      // get response from the server
      responseObject = JSON.parse(request.responseText);

      if (responseObject) {
        if (responseObject.ok) {
          // redirect user to the mainpage
          window.location = "./mainpage.php";
          // console.log("logged in");
        } else {
          clearOutputMessage(loginmessage);

          displayResponseObjectMessage(responseObject, loginmessage, "#fe0001");
          // responseObject.messages.forEach((message) => {
          //   displayMessages(message, loginmessage, "#fe0001");
          // });
        }
      }
    } else {
      console.log("Could not parse JSON!");
      loginmessage.innerHTML = `
      <div class="alert alert-danger">An error occured. Please try again later.</div>
      `;
    }
  });

  // get the data from the form and send to server
  const dataToPost = `email=${email.value}&password=${
    password.value
  }&rememberme=${remember_me ? true : ""}`;

  request.open("POST", "./login.php");

  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.send(dataToPost);
});

function displayMessages(message, targetElement, color = "#2c0faa") {
  const li = document.createElement("li");
  li.innerHTML = message;

  // color the text differently
  li.style.color = color;
  li.style.marginBottom = "12px";

  targetElement.appendChild(li);
}

function clearOutputMessage(targetElement) {
  while (targetElement.firstChild) {
    targetElement.firstChild.remove();
  }
}

function displayResponseObjectMessage(responseObject, targetElement, color) {
  responseObject.messages.forEach((msg) => {
    displayMessages(msg, targetElement, color);
  });
}

// if(username === "" || email === "" || password === "" || password2 === "") {
//   signupmessage.textContent = "<p>Kindly enter your</p>"
// }
// if (username === "") {
//   signupmessage.innerHTML = "<p>Kindly enter your username</p>";
// } else if (email === "") {
//   signupmessage.innerHTML = "<p>Kindly enter your username</p>";
// } else if (password === "") {
//   signupmessage.innerHTML = "<p>Kindly enter your password</p>";
// } else if (password !== password2) {
//   signupmessage.innerHTML =
//     "<p>Kindly confirm your password and make sure they match</p>";
// } else if (
//   username === "" ||
//   email === "" ||
//   password === "" ||
//   password2 === ""
// ) {
//   signupmessage.innerHTML =
//     "<p>Kindly enter valid values into all fields.</p>";
// }
