<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Projects Demo</title>
    <style>
        /* Base styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5F5F5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }

        section {
            width: 90%;
            max-width: 800px;
            margin-bottom: 40px;
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Header styles */
        h1 {
            font-size: 36px;
            font-weight: 400;
            color: #333;
            margin-bottom: 20px;
        }

        /* Input box styles */
        #apiKeyInput {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 18px;
            border: 2px solid #CCCCCC;
            border-radius: 8px;
            outline: none;
        }

        /* Save button styles */
        #saveButton {
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            background-color: #6200EA;
            color: #FFFFFF;
            cursor: pointer;
            margin-bottom: 30px;
            transition: background-color 0.3s ease-in-out;
        }

        #saveButton:hover {
            background-color: #3700B3;
        }

        /* Project button styles */
        .image-button {
            width: 100%;
            max-width: 300px;
            height: 150px;
            border: none;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: box-shadow 0.3s ease-in-out;
            overflow: hidden;
            background-color: #fff;
        }

        .image-button img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-button:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        /* Footer styles */
        footer {
            margin-top: 40px;
            color: #777;
        }

        footer a {
            color: #6200EA;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Hidden styles */
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <section>
        <h1>AI Projects Demo</h1>
    </section>

    <section>
        <h2>Step 1</h2>
        <p>The demo projects need a key for the Google Gemini API. If you don't have one, you can skip this step, but the projects may not work properly.</p>
        <input type="text" id="apiKeyInput" placeholder="Enter your API key" />
        <button id="saveButton">Save API Key</button>
        <br>
        <small><a href="https://aistudio.google.com/app/apikey" target="_blank">Sign up for an API Key at Google (requires a Google account)</a></small>
    </section>

    <section>
        <h2>Step 2</h2>
        <p>Which project would you like to visit?</p>

        <article class="project1">
            <h3>Bravo</h3>
            <p class="project-description">Bravo demonstrates how hospitals can use AI to build a privacy-first healthcare system that's efficient for doctors and delightful for patients.</p>
            <button class="image-button" id="bravoButton">
               <!-- <img src="bravo_image.png" alt="Bravo Project">-->
               CLICK HERE TO TRY BRAVO Project
            </button>
        </article>

        <article class="project2">
            <h3>Umbra</h3>
            <p class="project-description">Umbra is a tool that prevents your private data from being shared online. It uses Google Tensorflow to redact personal, sensitive, or confidential information on your local machine for use of Google Gemini With out showing you private data </p>
            <button class="image-button" id="umbraButton">
              <!-- <   <img src="umbra_image.png" alt="Umbra Project"> -->
               CLICK HERE TO TRY UMBRA TOOL
            </button>
        </article>
    </section>

    <footer>
        <p>Made by <a href="">Paul</a> &amp; <a href="https://github.com/vvaneli">Viviane</a> | &#169; 2024</p>
    </footer>

    <script>
        // Function to get URL parameter
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Check if API key exists in localStorage
        const savedApiKey = localStorage.getItem('apiKey');
        const apiKeyInput = document.getElementById('apiKeyInput');
        const saveButton = document.getElementById('saveButton');
        const bravoButton = document.getElementById('bravoButton');
        const umbraButton = document.getElementById('umbraButton');
        const urlApiKey = getQueryParam('key');

        // If key parameter exists in the URL
        if (urlApiKey) {
            apiKeyInput.value = urlApiKey;
            apiKeyInput.classList.remove('hidden');
            saveButton.classList.remove('hidden');
        }

        // If API key is already saved, hide the input and button
        if (savedApiKey && !urlApiKey) {
            apiKeyInput.classList.add('hidden');
            saveButton.classList.add('hidden');
        }

        // Save the API key in localStorage when button is clicked
        saveButton.addEventListener('click', () => {
            const apiKey = apiKeyInput.value.trim();
            if (apiKey) {
                localStorage.setItem('apiKey', apiKey);
                apiKeyInput.classList.add('hidden');
                saveButton.classList.add('hidden');
            }
        });

        // Set up the buttons to include the API key in the URL
        function appendApiKeyToUrl(baseUrl) {
            const apiKey = localStorage.getItem('apiKey');
            return `${baseUrl}${apiKey}`;
        }

        bravoButton.addEventListener('click', () => {
            const url = appendApiKeyToUrl('https://ai.qqmber.com/bravo/page.php?key=');
            window.location.href = url;
        });

        umbraButton.addEventListener('click', () => {
            const url = appendApiKeyToUrl('https://ai.qqmber.com/page.php?key=');
            window.location.href = url;
        });
    </script>
</body>
</html>