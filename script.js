// Global variable to hold the model
let model;

// Function to highlight text in the input
function highlightText() {
    const inputText = document.getElementById('box1').value;
    const markers = jsonData.gdpr_items;

    let highlightedText = inputText;
    markers.forEach(item => {
        const regex = new RegExp(item.look_for, 'gi');
        highlightedText = highlightedText.replace(regex, match => `<span class="highlight">${match}</span>`);
    });

    document.getElementById('box2').innerHTML = highlightedText;
}

// Function to replace sensitive information with placeholder text and store the original values
function replaceText() {
    const inputText = document.getElementById('box1').value;
    const markers = jsonData.gdpr_items;

    let resultText = inputText;

    markers.forEach(item => {
        const regex = new RegExp(item.look_for, 'gi');

        // Store matches in localStorage before replacing them
        resultText = resultText.replace(regex, match => {
            localStorage.setItem(item.item, match);
            return item.item; // Return the item to replace in the text
        });
    });

    document.getElementById('box3').value = resultText;
}

// Function to replace markers with their original values from localStorage
function back() {
    const inputText = document.getElementById('box4').value;
    const markers = jsonData.gdpr_items;

    let resultText = inputText;
    markers.forEach(item => {
        const regex = new RegExp(item.item, 'gi');
        resultText = resultText.replace(regex, item.look_for);
    });

    document.getElementById('box5').value = resultText;
}

// Function to send the text to an API and handle the response
function send2api() {
    const promptValue = document.getElementById('box3-prmpt').value;
    const textValue = document.getElementById('box3').value;

    const dataPrompt = `<prompt>${promptValue}</prompt>\n${textValue}`;
    const encodedX = encodeURIComponent(dataPrompt);

    const apiUrl = `https://qqmber.com/api/ai/send_promt.php?prompt=${encodedX}&key=${googletoken}`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data);
            const candidates = data.candidates;
            if (candidates && candidates.length > 0) {
                const textContent = candidates[0].content.parts[0].text;
                document.getElementById('box4').value = textContent;
            } else {
                document.getElementById('box4').value = "No text found in the response.";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('box4').value = "An error occurred. Check the console for details.";
        });
}

// Function to fetch JSON data (assuming json.php is replaced with the correct path)
async function fetchJsonData() {
    try {
        const response = await fetch('data.json'); // Replace data.json' with the correct path if necessary
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        jsonData = await response.json();
        console.log('Fetched JSON Data:', jsonData);
    } catch (error) {
        console.error('Failed to fetch JSON data:', error);
    }
}

// Call the function to fetch JSON data on page load
fetchJsonData();

// Function to get localStorage values and create a JSON object
function getLocalStorageAsJSON() {
    const localStorageData = {};
    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        const value = localStorage.getItem(key);
        localStorageData[key] = value;
    }
    return localStorageData;
}

// Function to replace markers in text using values from localStorage
function replaceMarkers() {
    const inputText = document.getElementById('box4').value;
    console.log('Original Text from box4:', inputText);

    const localStorageData = getLocalStorageAsJSON();
    console.log('LocalStorage Data:', localStorageData);

    let resultText = inputText;

    // Iterate over each key-value pair in localStorage
    Object.keys(localStorageData).forEach(key => {
        const value = localStorageData[key];
        const escapedKey = key.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

        const regex = new RegExp(`\\b${escapedKey}\\b[.,]*`, 'gi');

        resultText = resultText.replace(regex, match => value);
    });

    document.getElementById('box5').value = resultText;
}

// Function to share the resulting text
function share() {
    const inputText = document.getElementById('box5').value;
    alert(inputText);
}

// Load the QnA model and hide the spinner once loaded
document.getElementById('loading').style.display = 'block';
qna.load().then(loadedModel => {
    model = loadedModel;
    console.log("Model loaded successfully.");
    document.getElementById('loading').style.display = 'none'; // Hide spinner
}).catch(error => {
    console.error('Failed to load model:', error);
    document.getElementById('loading').style.display = 'none'; // Hide spinner
});

// Function to handle local AI processing
function send2Localai() {
    const passage = document.getElementById('box1').value;

    askQuestion('What is the full name of the person', passage).then(() => {
        askQuestion('Where is the street', passage);
    });
}

// Function to ask a question using the loaded model
function askQuestion(question, passage) {
    const resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = "Finding answers...";

    if (model && passage) {
        return model.findAnswers(question, passage).then(answers => {
            resultsDiv.innerHTML = ''; // Clear previous results

            if (answers.length > 1) {
                const answer = answers[1]; // Always take the 2nd answer (index 1)
                const key = question.includes('full name')
                    ? 'Person_Name_id1'
                    : 'Street_Name_id2';
                const value = answer.text;

                // Store the answer in local storage
                localStorage.setItem(key, value);

                // Highlight text in box1
                highlightText(value);

                resultsDiv.innerHTML = `<strong>Answer:</strong> ${answer.text} <br> <strong>Score:</strong> ${answer.score.toFixed(2)}`;
            } else {
                resultsDiv.innerHTML = 'Less than 2 answers found.';
            }
        });
    } else {
        resultsDiv.innerHTML = "Please ensure the model is loaded, and both question and passage are provided.";
    }
}