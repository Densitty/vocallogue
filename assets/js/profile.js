const newUsernameForm = document.getElementById("update_username_form");
const newEmailForm = document.getElementById("update_email_form");
const newPasswordForm = document.getElementById("update_password_form");

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

  request.addEventListener("load", () => {
    if (request.readyState === 4 && request.status === 200) {
      let responseObject = null;
      console.log(request);
      // get response from the server
      responseObject = JSON.parse(request.responseText);
      if (responseObject) {
        if (responseObject.ok) {
          console.log("Password changed");

          // emailMsg
          emailMsg.innerHTML = "";
          // remove the "alert-danger" class on it
          emailMsg.classList.remove("alert-danger");
          emailMsg.classList.add("alert", "alert-success");

          responseObject.messages.forEach((msg) => {
            const item = document.createElement("li");
            item.textContent = msg;
            emailMsg.insertAdjacentElement("beforeend", item);
          });
        } else {
          while (emailMsg.firstElementChild) {
            emailMsg.firstElementChild.remove();
          }

          emailMsg.classList.add("alert", "alert-danger");

          responseObject.messages.forEach((msg) => {
            const item = document.createElement("li");
            item.textContent = msg;
            emailMsg.insertAdjacentElement("beforeend", item);
          });
        }

        emailMsg.style.display = "block";
      }
    } else {
      emailMsg.innerHTML = `<p class="alert alert-danger">Error in updating your username. Please try again later.</p>`;
    }
  });

  const dataToPost = `newEmail=${newEmail.value}`;

  request.open("POST", "./update_email.php");

  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.send(dataToPost);
});

newPasswordForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const updatePasswordMsg = document.getElementById("updatepasswordmessage");
  const currentPassword = e.target[1];
  const newPassword = e.target[2];
  const comfirmNewPassword = e.target[3];

  const request = new XMLHttpRequest();

  request.addEventListener("load", () => {
    if (request.readyState === 4 && request.status === 200) {
      let responseObject = null;
      console.log(request);
      // get response from the server
      responseObject = JSON.parse(request.responseText);
      if (responseObject) {
        if (responseObject.ok) {
          // empty the content of updatePasswordMsg div
          updatePasswordMsg.innerHTML = "";
          // remove the "alert-danger" class on it
          updatePasswordMsg.classList.remove("alert-danger");
          updatePasswordMsg.classList.add("alert", "alert-success");

          responseObject.messages.forEach((msg) => {
            const item = document.createElement("li");
            item.textContent = msg;
            updatePasswordMsg.insertAdjacentElement("beforeend", item);
          });
          // if name change is successful, reload the page
          setTimeout(() => {
            location.reload();
          }, 10000);
        } else {
          while (updatePasswordMsg.firstElementChild) {
            updatePasswordMsg.firstElementChild.remove();
          }

          updatePasswordMsg.classList.add("alert", "alert-danger");

          responseObject.messages.forEach((msg) => {
            const item = document.createElement("li");
            item.textContent = msg;
            updatePasswordMsg.insertAdjacentElement("beforeend", item);
          });
        }

        updatePasswordMsg.style.display = "block";
      }
    } else {
      updatePasswordMsg.innerHTML = `<p class="alert alert-danger">Error in updating your username. Please try again later.</p>`;
      updatePasswordMsg.classList.add("alert", "alert-danger");
    }
  });

  const dataToPost = `currentPassword=${currentPassword.value}&newPassword=${newPassword.value}&confirmNewPassword=${comfirmNewPassword.value}`;

  request.open("POST", "./update_password.php");

  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.send(dataToPost);
});
