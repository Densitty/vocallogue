window.addEventListener("load", () => {
  loadWordsFromServer();
  // const words = document.querySelectorAll(".noteheader");

  // console.log(words);
});

function getElement(selector) {
  if (!selector) return null;
  return document.querySelector(selector);
}

// load the variables
const addWord = getElement("#addWord");
const editWord = getElement("#edit");
const submitWord = getElement("#done");
const loadAllWords = getElement("#all-words");
const wordPad = getElement("#wordpad");
const wordsDiv = getElement("#words");
const displayBtns = getElement(".display_btns");
const editBtns = getElement(".edit_btns");
const mainPageContainer = getElement(".mainpage");
const wordText = getElement("#word_text");
const wordNotes = getElement("#word_notes");
let activeNoteId;
let editMode = false;
// all words created

// words.forEach((word) => {
//   console.log(word);
// });
wordsDiv.addEventListener("click", (e) => {
  let targetElement;
  // display wordPad only when the wordText or date is clicked
  if (e.target.parentElement.classList.contains("noteheader")) {
    targetElement = e.target.parentElement;
    // update the activeNoteId
    activeNoteId = targetElement.id;

    // display wordPad related btns
    wordPad.style.display = "block";
    editBtns.style.display = "block";
    // hide wordDiv & related btns
    displayBtns.style.display = "none";
    wordsDiv.style.display = "none";
    // insert the words into related input areas
    wordText.value = targetElement.children[0].textContent;
    wordNotes.value = targetElement.children[1].textContent;
  }
});

// click on the addWord btn to create a form to enter the new word
addWord.addEventListener("click", enterNewWord);
// click on the Done btn to submit the created word and the notes attached
loadAllWords.addEventListener("click", submitNewWord);
// load the words (both word and notes) by making a request to load_words.php
function loadWordsFromServer() {
  displayBtns.style.display = "block";
  editBtns.style.display = "none";

  // console.log("getting words from the server");
  const request = new XMLHttpRequest();

  request.addEventListener("load", () => {
    if (request.readyState === 4 && request.status === 200) {
      let responseObject = null;

      // get response from the server
      responseObject = JSON.parse(request.responseText);

      if (responseObject) {
        if (responseObject.ok) {
          clearOutputMessage(words);
          let wordsResult = [];

          responseObject.messages.forEach((words) => {
            wordsResult = [...words];
          });

          wordsResult.forEach((words) => {
            const { id, time, notes, word } = words;
            // create different elements to hold the response from the server
            const outerDiv = document.createElement("div");
            const wordDisplay = document.createElement("p");
            const wordNote = document.createElement("p");
            const dateAdded = document.createElement("p");
            // append the word and date created to their respective elements
            wordDisplay.textContent = word;
            wordNote.textContent = notes;
            dateAdded.textContent = time;
            // add classes and id to the elements
            outerDiv.classList.add("noteheader");
            outerDiv.id = id;

            wordDisplay.classList.add("text");
            wordNote.classList.add("note_text");
            dateAdded.classList.add("timetext");

            // append the word & time created to the beginning and end of the parent container (outerDiv) respectively
            outerDiv.insertAdjacentElement("afterbegin", wordDisplay);
            wordDisplay.insertAdjacentElement("afterend", wordNote);
            outerDiv.insertAdjacentElement("beforeend", dateAdded);

            // append outerDiv to the parent container holding all words
            wordsDiv.insertAdjacentElement("afterbegin", outerDiv);
            // wordsDiv.appendChild(outerDiv);
          });

          wordPad.style.display = "none";
        } else {
          clearOutputMessage(wordsDiv);

          responseObject.messages.forEach((res) => {
            wordsDiv.innerHTML =
              `
      <div class="alert alert-danger">` +
              res +
              `</div>`;
          });
        }
      }
    } else {
      words.innerHTML = `
      <div class="alert alert-danger">An error occured. Please try again later.</div>
      `;
    }
  });

  // get the data from the form and send to server
  // const dataToPost = `email=${email.value}&password=${
  //   password.value
  // }&rememberme=${remember_me ? true : ""}`;

  request.open("GET", "./load_words.php");

  // request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.send();
}

// function displayMessages(message, targetElement, color = "#2c0faa") {
//   const li = document.createElement("li");
//   li.innerHTML = message;

//   // color the text differently
//   li.style.color = color;
//   li.style.marginBottom = "12px";

//   targetElement.appendChild(li);
// }

function clearOutputMessage(targetElement) {
  while (targetElement.firstChild) {
    targetElement.firstChild.remove();
  }
}

// function displayResponseObjectMessage(responseObject, targetElement, color) {
//   responseObject.forEach((msg) => {
//     displayMessages(msg, targetElement, color);
//   });
// }

// add new words by making a call to create_word.php
function enterNewWord() {
  // make a request to the create_word.php
  const request = new XMLHttpRequest();
  request.addEventListener("load", () => {
    if (request.readyState === 4 && request.status === 200) {
      let responseObject = null;
      // get response from the server
      responseObject = JSON.parse(request.responseText);
      if (responseObject) {
        if (responseObject.ok) {
          // display the wordPad & related btns
          editBtns.style.display = "block";
          wordPad.style.display = "block";
          // hide wordsDiv & related btns
          displayBtns.style.display = "none";
          wordsDiv.style.display = "none";

          activeNoteId = responseObject.messages[0];
        } else {
          // console.log(alertContent);
          // const alertBox = document.createElement("div");
          // alertBox.classList.add("alert", "alert-danger", "collapse");
          // alertBox.id = "alert";

          // const closeAlert = document.createElement("a");
          // closeAlert.classList.add("close");
          // closeAlert.setAttribute("data-dismiss", "alert");
          // closeAlert.innerHTML = "&#10005;";

          // const alertContent = document.createElement("p");
          // alertContent.id = "alert_content";

          // alertContent.textContent = responseObject.messages[0];
          // // alert.appendChild(alertContent);
          // alertBox.insertAdjacentElement("afterbegin", closeAlert);
          // alertBox.insertAdjacentElement("beforeend", alertContent);

          // if (getElement("#alert") === null) {
          //   alertBox.style.display = "block";
          //   mainPageContainer.insertAdjacentElement("afterbegin", alertBox);
          // }

          displayAlertMessage(responseObject.messages[0]);
          // alert.style.opacity = "1";
          // alert.style.visibility = "visible"alert.
          // if (alertContent.textContent !== "") {
          //   alert.style.display = "block";
          //   console.log("Error message available");
          // }
        }
      }
    } else {
      // console.log("Error in processing your request at this time.");
      displayAlertMessage();
    }
  });

  const dataToPost = ``;

  request.open("GET", "./create_word.php");

  request.send();
}

function submitNewWord() {
  console.log("submitting the new word");

  wordPad.style.display = "none";
  wordsDiv.style.display = "block";
  // load the words again
  loadWordsFromServer();
}

function displayAlertMessage(
  alertContentMsg = "There was an error. Please try again later."
) {
  const alertBox = document.createElement("div");
  alertBox.classList.add("alert", "alert-danger", "collapse");
  alertBox.id = "alert";

  const closeAlert = document.createElement("a");
  closeAlert.classList.add("close");
  closeAlert.setAttribute("data-dismiss", "alert");
  closeAlert.innerHTML = "&#10005;";

  const alertContent = document.createElement("p");
  alertContent.id = "alert_content";

  createAlertBoxMessage(alertContent, alertContentMsg);
  // alert.appendChild(alertContent);
  alertBox.insertAdjacentElement("afterbegin", closeAlert);
  alertBox.insertAdjacentElement("beforeend", alertContent);

  if (getElement("#alert") === null) {
    alertBox.style.display = "block";
    mainPageContainer.insertAdjacentElement("afterbegin", alertBox);
  }
}

function createAlertBoxMessage(element, msg) {
  element.textContent = msg;
}

// type into the word_notes by calling the update_word.php
submitWord.addEventListener("click", (e) => {
  // make a request to the create_word.php
  const request = new XMLHttpRequest();

  request.addEventListener("load", () => {
    if (request.readyState === 4 && request.status === 200) {
      let responseObject = null;
      // get response from the server
      // console.log(request);
      responseObject = JSON.parse(request.responseText);
      if (responseObject) {
        if (responseObject.ok) {
          console.log(responseObject);
          // load back all words
          submitNewWord();
        } else {
          const message =
            "There was an issue updating your word text in the database.";

          displayAlertMessage(message);
        }
      }
    } else {
      // console.log("Error in processing your request at this time.");
      displayAlertMessage();
    }
  });

  const dataToPost = `wordText=${wordText.value}&wordNotes=${wordNotes.value}&activeNoteId=${activeNoteId}`;

  request.open("POST", "./update_word.php");

  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  request.send(dataToPost);

  // reset all inputs back to empty
  wordText.value = "";
  wordNotes.value = "";
});

// display the wordPad & related btns
function showWordPad() {
  wordPad.style.display = "block";
  editBtns.style.display = "block";
}

// hide wordsDiv & related btns
function hideWordDiv() {
  displayBtns.style.display = "none";
  wordsDiv.style.display = "none";
}
