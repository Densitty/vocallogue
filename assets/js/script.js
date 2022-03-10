// make a request to submit the sign-up form
const signup = document.getElementById("signupform");

// get the signupmessage div
const signupmessage = document.getElementById("signupmessage");

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
    // try {
    //   console.log(xhr.responseText);
    // } catch (e) {
    //   console.log("Could not parse JSON!");
    // }
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
    while (signupmessage.firstChild) {
      signupmessage.firstChild.remove();
    }

    // now display the messages from server in DOM
    responseObject.messages.forEach((msg) => {
      const li = document.createElement("li");
      li.innerHTML = msg;
      // color the text differently
      li.style.color = "#2c0faa";
      li.style.marginBottom = "12px";

      signupmessage.appendChild(li);
    });
  } else {
    // target the response message display in DOM and empty it
    while (signupmessage.firstChild) {
      signupmessage.firstChild.remove();
    }

    // now display the messages from server in DOM
    responseObject.messages.forEach((msg) => {
      const li = document.createElement("li");
      li.innerHTML = msg;

      signupmessage.appendChild(li);
    });
  }
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
