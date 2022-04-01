// load the variables
function getElement(selector) {
  if (!selector) return null;
  return document.querySelector(selector);
}

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
// variable to hold id of each word
let activeNoteId;

// load all words from the server on loading the page
window.addEventListener("load", loadWordsFromServer);

// delete or edit the word based on the location clicked on the UI
wordsDiv.addEventListener("click", editOrDeleteWord);

// click on the addWord btn to create a form to enter the new word
addWord.addEventListener("click", enterNewWord);

// click on the Done btn to submit the created word and the notes attached
loadAllWords.addEventListener("click", submitNewWord);

// type into the word_notes by calling the update_word.php
submitWord.addEventListener("click", addNotesToNewlyCreatedWord);

/* All functions */
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

          /* structured it like this due to the way I sent my response from PHP, not understood JSON response propertly unlike that of JS/Node */
          // if there is no word in our log yet
          if (typeof responseObject.messages[0] === "string") {
            wordsDiv.classList.add("noteheader");
            wordsDiv.textContent = responseObject.messages[0];
            return;
          }

          responseObject.messages.forEach((words) => {
            wordsResult = [...words];
          });

          if (wordsResult.length > 0) {
            wordsResult.forEach((words) => {
              const { id, time, notes, word } = words;
              // create different elements to hold the response from the server
              const outerDiv = document.createElement("div");
              const wordDisplay = document.createElement("p");
              const wordNote = document.createElement("p");
              const dateAdded = document.createElement("p");
              const deleteBtn = document.createElement("div");

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
              deleteBtn.classList.add("col-xs-5", "col-sm-3", "deleteBtn");

              deleteBtn.textContent = "\u2716";
              // append the word & time created to the beginning and end of the parent container (outerDiv) respectively
              outerDiv.insertAdjacentElement("beforebegin", deleteBtn);
              outerDiv.appendChild(deleteBtn);
              outerDiv.insertAdjacentElement("afterbegin", wordDisplay);
              wordDisplay.insertAdjacentElement("afterend", wordNote);
              outerDiv.insertAdjacentElement("beforeend", dateAdded);

              // append outerDiv to the parent container holding all words
              wordsDiv.insertAdjacentElement("afterbegin", outerDiv);
              // wordsDiv.appendChild(outerDiv);
              // outerDiv.insertAdjacentElement("afterbegin", deleteBtn);
              // outerDiv.insertAdjacentElement("beforeend", wordsDiv);
            });
          }

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

  request.open("GET", "./load_words.php");

  request.send();
}

function clearOutputMessage(targetElement) {
  while (targetElement.firstChild) {
    targetElement.firstChild.remove();
  }
}

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
          /* editBtns.style.display = "block";
          wordPad.style.display = "block"; */
          showWordPad();
          // hide wordsDiv & related btns
          /* displayBtns.style.display = "none";
          wordsDiv.style.display = "none"; */
          hideWordDiv();

          activeNoteId = responseObject.messages[0];
        } else {
          displayAlertMessage(responseObject.messages[0]);
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

function editOrDeleteWord(e) {
  // console.log(e.target);

  let targetElement;
  // display wordPad only when the wordText or date is clicked
  if (
    e.target.parentElement.classList.contains("noteheader") &&
    !e.target.classList.contains("deleteBtn")
  ) {
    targetElement = e.target.parentElement;
    // update the activeNoteId
    activeNoteId = targetElement.id;

    // display wordPad related btns
    showWordPad();
    // hide wordDiv & related btns
    hideWordDiv();
    // insert the words into related input areas
    wordText.value = e.target.parentElement.children[0].textContent;
    wordNotes.value = e.target.parentElement.children[1].textContent;
  }

  // if the 'x' btn area is clicked, remove the word entry
  if (e.target.classList.contains("deleteBtn")) {
    activeNoteId = e.target.parentElement.id;

    const request = new XMLHttpRequest();

    request.addEventListener("load", () => {
      if (request.readyState === 4 && request.status === 200) {
        let responseObject = null;
        // get response from the server
        // console.log(request);
        responseObject = JSON.parse(request.responseText);
        if (responseObject) {
          if (responseObject.ok) {
            // console.log(responseObject);
            // remove the word from ui if successfully removed from db
            e.target.parentElement.remove();
            // make call to server to load all words again
            loadWordsFromServer();
          } else {
            const message = responseObject.messages[0];

            displayAlertMessage(message);
          }
        }
      } else {
        displayAlertMessage();
      }
    });

    const dataToPost = `activeNoteId=${activeNoteId}`;

    request.open("POST", "./delete_word.php");

    request.setRequestHeader(
      "Content-type",
      "application/x-www-form-urlencoded"
    );

    request.send(dataToPost);

    // console.log(e.target.parentElement.remove());
  }
}

function addNotesToNewlyCreatedWord() {
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
          // console.log(responseObject);
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
}

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
