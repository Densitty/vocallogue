window.addEventListener("load", loadWordsFromServer);

function getElement(id) {
  if (!id) return null;
  return document.getElementById(id);
}
// load the variables
const addWord = getElement("addWord");
const editWord = getElement("editWord");
const submitWord = getElement("done");
const loadWords = getElement("all-words");
const wordPad = getElement("wordpad");
const wordsDiv = getElement("words");

// load the words (both word and notes) by making a request to load_words.php
// add new words by making a call to create_word.php
// type into the word_notes by calling the update_word.php
//
function loadWordsFromServer() {
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
            const dateAdded = document.createElement("p");
            // append the word and date created to their respective elements
            wordDisplay.textContent = word;
            dateAdded.textContent = time;
            // add classes and id to the elements
            outerDiv.classList.add("noteheader");
            outerDiv.id = id;

            wordDisplay.classList.add("text");
            dateAdded.classList.add("timetext");

            // append the word & time created to the beginning and end of the parent container (outerDiv) respectively
            outerDiv.insertAdjacentElement("afterbegin", wordDisplay);
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
