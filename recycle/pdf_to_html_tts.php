<?php
// Single PHP file for PDF Upload, Convert to HTML, Phonetics, TTS, and narrator

// Helper function to sanitize output text
function sanitize($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

$convertedText = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
    $uploadDir = __DIR__ . '/uploads';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $fileTmp = $_FILES['pdf_file']['tmp_name'];
    $fileName = basename($_FILES['pdf_file']['name']);
    $filePath = $uploadDir . '/' . $fileName;

    if (!move_uploaded_file($fileTmp, $filePath)) {
        $error = "Failed to upload file.";
    } else {
        // Attempt to convert PDF to text using pdftotext CLI
        $txtPath = $uploadDir . '/' . pathinfo($fileName, PATHINFO_FILENAME) . '.txt';
        $escapedFilePath = escapeshellarg($filePath);
        $escapedTxtPath = escapeshellarg($txtPath);
        $cmd = "pdftotext -layout $escapedFilePath $escapedTxtPath";
        exec($cmd, $output, $retval);
        if ($retval !== 0 || !file_exists($txtPath)) {
            $error = "Error converting PDF to text. Ensure pdftotext is installed on the server.";
        } else {
            $convertedText = file_get_contents($txtPath);
            // Clean up uploaded files after use (optional)
            // unlink($filePath);
            // unlink($txtPath);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>PDF to HTML with TTS, Phonetics & Narrator</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 20px;
        background: #f9f9f9;
        color: #333;
    }
    h1 {
        text-align: center;
        color: #2c3e50;
    }
    form {
        text-align: center;
        margin-bottom: 20px;
    }
    input[type=file] {
        padding: 8px;
    }
    input[type=submit] {
        padding: 10px 15px;
        font-size: 1rem;
        cursor: pointer;
        background: #3498db;
        border: none;
        border-radius: 4px;
        color: #fff;
        transition: background 0.3s ease;
    }
    input[type=submit]:hover {
        background: #2980b9;
    }
    #content {
        max-width: 700px;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        line-height: 1.6;
        font-size: 18px;
        user-select: text;
    }
    .word {
        cursor: pointer;
        position: relative;
        padding: 2px 3px;
        border-radius: 3px;
        transition: background 0.3s ease;
    }
    .word:hover {
        background: #d1e7fd;
    }
    #popup {
        position: absolute;
        background: #ffffff;
        border: 1px solid #3498db;
        padding: 12px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
        max-width: 320px;
        display: none;
        z-index: 1000;
        font-size: 14px;
        color: #222;
    }
    #popup h3 {
        margin-top: 0;
        margin-bottom: 6px;
        font-size: 1.1rem;
        color: #1a5276;
    }
    #popup p {
        margin: 6px 0;
    }
    #popup button {
        background: #3498db;
        border: none;
        color: white;
        padding: 6px 10px;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }
    #popup button:hover {
        background: #2980b9;
    }
    #popup button svg {
        width: 16px;
        height: 16px;
        margin-left: 6px;
        fill: white;
    }
    #narratorBtn {
        display: block;
        margin: 10px auto 20px;
        padding: 12px 20px;
        font-size: 16px;
        border-radius: 6px;
        background: #27ae60;
        color: white;
        border: none;
        cursor: pointer;
        max-width: 200px;
        text-align: center;
        user-select: none;
        transition: background 0.3s ease;
    }
    #narratorBtn:disabled {
        background: #95a5a6;
        cursor: not-allowed;
    }
    #narratorBtn:hover:not(:disabled) {
        background: #1e8449;
    }
    @media (max-width: 600px) {
        body {
            margin: 10px;
        }
        #content {
            font-size: 16px;
            padding: 15px;
        }
        #popup {
            max-width: 90vw;
            font-size: 13px;
        }
        #narratorBtn {
            max-width: 100%;
            font-size: 14px;
            padding: 10px;
        }
    }
</style>
</head>
<body>
    <h1>PDF to HTML with TTS, Phonetics & Narrator</h1>

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="pdf_file" accept="application/pdf" required/>
        <input type="submit" value="Upload & Convert"/>
    </form>

    <?php if ($error): ?>
        <p style="color: red; text-align:center; font-weight: bold;"><?=sanitize($error)?></p>
    <?php endif; ?>

    <?php if ($convertedText): ?>
        <button id="narratorBtn">▶️ Narrate Full Text</button>
        <div id="content" aria-live="polite" aria-label="Converted PDF Text">
            <?php
            // Split text into words and punctuation, wrap words in spans
            // Use regex to split by word boundaries preserving spaces and punctuations
            $parts = preg_split('/(\s+|[.,!?;:"\'\-\(\)\[\]])/', $convertedText, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            foreach ($parts as $part) {
                if (preg_match('/^\w+$/u', $part)) {
                    // It's a word, wrap in span
                    echo '<span class="word" tabindex="0">' . sanitize($part) . '</span>';
                } else {
                    // punctuation or whitespace, output as is
                    echo sanitize($part);
                }
            }
            ?>
        </div>
    <?php endif; ?>

    <div id="popup" role="dialog" aria-modal="true" aria-live="assertive" aria-hidden="true">
        <h3 id="popup-word">Word</h3>
        <p><strong>Phonetic: </strong><span id="popup-phonetic">-</span></p>
        <p><strong>Meaning: </strong><span id="popup-meaning">-</span></p>
        <button id="playPronunciation" aria-label="Play pronunciation">
            🔊
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10v4l6 4V6l-6 4zm13.5 2c0-1.77-1.02-3.29-2.5-4.03v8.06c1.48-.73 2.5-2.25 2.5-4.03z"></path></svg>
        </button>
    </div>

<script>
    // Globals
    const popup = document.getElementById('popup');
    const popupWord = document.getElementById('popup-word');
    const popupPhonetic = document.getElementById('popup-phonetic');
    const popupMeaning = document.getElementById('popup-meaning');
    const playBtn = document.getElementById('playPronunciation');
    let currentAudio = null;
    let currentWord = '';

    // Free dictionary API: https://api.dictionaryapi.dev/
    // Free TTS using Google Translate unofficial API for simplicity (can also use other free services)

    function playAudio(url) {
        if (currentAudio) {
            currentAudio.pause();
            currentAudio = null;
        }
        currentAudio = new Audio(url);
        currentAudio.play();
    }

    async function getPhoneticsAndMeaning(word) {
        try {
            const response = await fetch(`https://api.dictionaryapi.dev/api/v2/entries/en/${word.toLowerCase()}`);
            if (!response.ok) throw new Error('No data found');
            const data = await response.json();
            if (!data || !Array.isArray(data) || data.length === 0) throw new Error('No data found');
            const entry = data[0];
            let phoneticText = entry.phonetic || (entry.phonetics && entry.phonetics.length ? entry.phonetics[0].text : '');
            if (!phoneticText) phoneticText = '-';

            let meaningText = '';
            if (entry.meanings && entry.meanings.length) {
                const defs = entry.meanings[0].definitions;
                meaningText = defs && defs.length ? defs[0].definition : '-';
            } else {
                meaningText = '-';
            }
            return { phonetic: phoneticText, meaning: meaningText };
        } catch (e) {
            return { phonetic: '-', meaning: 'No information found.' };
        }
    }

    // Google Translate TTS URL (unofficial, no API key required)
    // Example: https://translate.google.com/translate_tts?ie=UTF-8&q=word&tl=en&client=tw-ob
    function getTTSUrl(text) {
        const base = 'https://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob';
        const params = new URLSearchParams({
            q: text,
            tl: 'en'
        });
        return `${base}&${params.toString()}`;
    }

    // Event handler for word click/focus (keyboard accessible)
    async function showPopup(event) {
        const target = event.target;
        if (!target.classList.contains('word')) return;

        currentWord = target.textContent;
        popupWord.textContent = currentWord;
        popupPhonetic.textContent = 'Loading...';
        popupMeaning.textContent = 'Loading...';
        popup.setAttribute('aria-hidden', 'false');
        popup.style.display = 'block';

        const { phonetic, meaning } = await getPhoneticsAndMeaning(currentWord);
        popupPhonetic.textContent = phonetic;
        popupMeaning.textContent = meaning;

        // Position popup near the word on screen
        const rect = target.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
        // Show above the word if possible, otherwise below
        let top = rect.top + scrollTop - popup.offsetHeight - 8;
        if (top < scrollTop) {
            top = rect.bottom + scrollTop + 8;
        }
        let left = rect.left + scrollLeft;
        // Prevent overflow right
        if (left + popup.offsetWidth > window.innerWidth) {
            left = window.innerWidth - popup.offsetWidth - 10;
        }
        popup.style.top = top + 'px';
        popup.style.left = left + 'px';
    }

    // Hide popup on clicking outside
    document.addEventListener('click', (e) => {
        if (!popup.contains(e.target) && !e.target.classList.contains('word')) {
            popup.style.display = 'none';
            popup.setAttribute('aria-hidden', 'true');
            if (currentAudio) {
                currentAudio.pause();
                currentAudio = null;
            }
        }
    });

    // Play pronunciation button
    playBtn.addEventListener('click', () => {
        if (!currentWord) return;
        const url = getTTSUrl(currentWord);
        playAudio(url);
    });

    // Attach click and keydown (Enter, Space) event listeners to words for accessibility
    document.getElementById('content')?.addEventListener('click', showPopup);
    document.getElementById('content')?.addEventListener('keydown', (e) => {
        if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('word')) {
            e.preventDefault();
            showPopup({target: e.target});
        }
    });

    // Narrator (read full text)
    const narratorBtn = document.getElementById('narratorBtn');
    if (narratorBtn) {
        narratorBtn.addEventListener('click', () => {
            narratorBtn.disabled = true;
            narratorBtn.textContent = '⏳ Narrating...';

            // Get full cleaned text from #content ignoring hidden spans
            const text = Array.from(document.querySelectorAll('#content .word'))
                .map(span => span.textContent)
                .join(' ');

            // Split text into chunks (max 200 chars) for TTS limits
            let chunks = [];
            let currentChunk = '';
            text.split(' ').forEach(word => {
                if ((currentChunk + ' ' + word).length > 150) {
                    chunks.push(currentChunk.trim());
                    currentChunk = word;
                } else {
                    currentChunk += ' ' + word;
                }
            });
            if (currentChunk.trim()) chunks.push(currentChunk.trim());

            let index = 0;

            function playNextChunk() {
                if (index >= chunks.length) {
                    narratorBtn.disabled = false;
                    narratorBtn.textContent = '▶️ Narrate Full Text';
                    return;
                }
                let url = getTTSUrl(chunks[index]);
                let audio = new Audio(url);
                audio.onended = () => {
                    index++;
                    playNextChunk();
                };
                audio.onerror = () => {
                    // Continue even on error
                    index++;
                    playNextChunk();
                };
                audio.play();
            }
            playNextChunk();
        });
    }
</script>
</body>
</html>

