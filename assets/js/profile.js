const newUsernameForm = document.getElementById("update_username_form");
const newEmailForm = document.getElementById("update_email_form");
const newPasswordForm = document.getElementById("update_email_form");

newUsernameForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const newUsername = e.target[1];
  const usernameMsg = document.getElementById("username_change");
  const request = new XMLHttpRequest();

  request.addEventListener("load", () => {
    if (request.readyState === 4 && request.status === 200) {
      let responseObject = null;
      console.log(request);
      // get response from the server
      responseObject = JSON.parse(request.responseText);
      if (responseObject) {
        if (responseObject.ok) {
          // console.log("Username successfully changed");
          // if name change is successful, reload the page
          location.reload();
        } else {
          usernameMsg.innerHTML = `<p class="alert alert-danger">${responseObject.messages}</p>`;
        }
      }
    } else {
      usernameMsg.innerHTML = `<p class="alert alert-danger">Error in updating your username. Please try again later.</p>`;
    }
  });

  const dataToPost = `newUsername=${newUsername.value}`;

  request.open("POST", "./update_username.php");

  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.send(dataToPost);
});

newEmailForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const newEmail = e.target[1];
  const emailMsg = document.getElementById("email_change");
  const request = new XMLHttpRequest();

  // request.addEventListener("load", () => {
  //   if (request.readyState === 4 && request.status === 200) {
  //     let responseObject = null;
  //     console.log(request);
  //     // get response from the server
  //     responseObject = JSON.parse(request.responseText);
  //     if (responseObject) {
  //       if (responseObject.ok) {
  //         // console.log("Username successfully changed");
  //         // if name change is successful, reload the page
  //         location.reload();
  //       } else {
  //         emailMsg.innerHTML = `<p class="alert alert-danger">${responseObject.messages}</p>`;
  //       }
  //     }
  //   } else {
  //     emailMsg.innerHTML = `<p class="alert alert-danger">Error in updating your username. Please try again later.</p>`;
  //   }
  // });

  const dataToPost = `newEmail=${newEmail.value}`;

  // request.open("POST", "./update_username.php");

  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.send(dataToPost);
});
newPasswordForm.addEventListener("submit", (e) => {
  e.preventDefault();
  console.log(e.target);
});
