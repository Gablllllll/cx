<?php
include "myconnector.php";
session_start();

if (!isset($_GET['file_url'])) {
    die("No file selected.");
}

$file_url = urldecode($_GET['file_url']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules - ClassXic</title>
    <link rel="stylesheet" href="css/modules.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <!-- Burger Menu -->
        <div class="burger-menu" onclick="toggleSidebar()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <!-- Title -->
        <div class="nav-center">ClassXic</div>
        <!-- User Info -->
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
            <img src="Images/user-svgrepo-com.svg" alt="User Icon">
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <ul class="sidebar-nav">
                <li><a href="landingpage.php"><img src="Images/home-svgrepo-com.svg" alt="Home Icon"> Home</a></li>           
                <li><a href="studentmodule.php"><img src="Images/book-svgrepo-com.svg" alt="Modules Icon"> Modules</a></li>
            </ul>
            <div class="sidebar-bottom">
                <ul class="sidebar-options">
                    <li>
                        <a href="#" class="dropdown-toggle"><img src="Images/option.png" alt="Option Icon">Option</a>
                        <ul class="dropdown-menu">
                          
                            <li><a href="settings.php"><img src="Images/settings-2-svgrepo-com.svg" alt="Settings Icon"> Settings</a></li>
                            <li><a href="logout.php"><img src="Images/logout-svgrepo-com.svg" alt="Logout Icon">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-header">
            <h1>PDF Module Viewer</h1>
            <p>Interactive learning with text-to-speech and pronunciation features</p>
        </div>

        <div class="controls-section">
            <div class="control-group">
                <button id="playPauseBtn" class="control-btn primary-btn" disabled>
                    <span>▶️ Play</span>
                </button>
                <button id="stopBtn" class="control-btn secondary-btn" disabled>
                    <span>⏹️ Stop</span>
                </button>
            </div>
            
            <div class="control-group">
                <label for="speedControl">Speed:</label>
                <input type="range" id="speedControl" min="0.5" max="2" step="0.1" value="1" class="speed-slider">
                <span id="speedValue" class="speed-value">1x</span>
            </div>
            
            <div class="control-group">
                <button id="highlightNarrateBtn" class="control-btn accent-btn" disabled>
                    <span>🎤 Narrate Highlighted</span>
                </button>
            </div>
            
            <div class="control-group">
                <label for="voiceSelect">Voice:</label>
                <select id="voiceSelect" class="voice-select">
                    <option value="" selected>Select Voice</option>
                </select>
            </div>
        </div>

        <div class="pdf-content-container">
            <div id="content" tabindex="0" aria-live="polite" aria-label="Converted PDF text will appear here" class="pdf-content"></div>
        </div>

        <!-- Word Information Popup -->
        <div id="popup" role="dialog" aria-modal="true" aria-live="assertive" aria-hidden="true" class="word-popup">
            <div class="popup-header">
                <h3 id="popup-word">Word</h3>
                <button class="close-popup" onclick="closePopup()">&times;</button>
            </div>
            <div class="popup-content">
                <div class="popup-info">
                    <p><strong>Phonetic:</strong> <span id="popup-phonetic">-</span></p>
                    <p><strong>Meaning:</strong> <span id="popup-meaning">-</span></p>
                </div>
                <button id="playPronunciation" class="pronunciation-btn" aria-label="Play pronunciation">
                    🔊 Play Pronunciation
                </button>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.8.162/pdf.min.js"></script>
    <script src="script/modules.js"></script>
    <script>
        // Sidebar functionality - moved to top so it's available immediately
       
        (() => {
            const contentDiv = document.getElementById('content');
            const playPauseBtn = document.getElementById('playPauseBtn');
            const stopBtn = document.getElementById('stopBtn');
            const speedControl = document.getElementById('speedControl');
            const speedValue = document.getElementById('speedValue');
            const highlightNarrateBtn = document.getElementById('highlightNarrateBtn');
            const popup = document.getElementById('popup');
            const popupWord = document.getElementById('popup-word');
            const popupPhonetic = document.getElementById('popup-phonetic');
            const popupMeaning = document.getElementById('popup-meaning');
            const playBtn = document.getElementById('playPronunciation');
            const voiceSelect = document.getElementById('voiceSelect');
            let voices = [];

            let pdfText = '';
            let currentWord = '';
            let wordBoundaries = [];
            let wordSpans = [];
            let utterance = null;
            let isPaused = false;
            let currentWordIdx = 0;
            let startWordIdx = 0;

			// Simple heuristic syllabifier for English words
			function syllabifyWord(word) {
				const w = (word || '').toLowerCase();
				if (!w) return '';
				const vowels = 'aeiouy';
				const parts = [];
				let current = '';
				for (let i = 0; i < w.length; i++) {
					const ch = w[i];
					const next = w[i + 1];
					const next2 = w[i + 2];
					current += ch;
					// Break pattern: V C V  => split after the vowel (VC · V)
					if (vowels.includes(ch) && next && !vowels.includes(next) && next2 && vowels.includes(next2)) {
						parts.push(current);
						current = '';
					}
					// Optional break for double consonant between vowels: V CC V => prefer V C · CV
					else if (
						vowels.includes(ch) &&
						next && !vowels.includes(next) &&
						next2 && !vowels.includes(next2) &&
						w[i + 3] && vowels.includes(w[i + 3])
					) {
						// keep first consonant with previous syllable
						current += next;
						i += 1;
						parts.push(current);
						current = '';
					}
				}
				if (current) parts.push(current);
				return parts.join('·');
			}

            // Helper for TTS highlighting
            function highlightWord(index) {
                document.querySelectorAll('.word').forEach(span => {
                    span.classList.remove('tts-highlight');
                });
                const span = document.querySelector(`.word[data-word-index="${index}"]`);
                if (span) {
                    span.classList.add('tts-highlight');
                }
            }

            // Clear all TTS highlights and active text selection
            function clearTTSHighlight() {
                document.querySelectorAll('.word.tts-highlight').forEach(span => {
                    span.classList.remove('tts-highlight');
                });
                const sel = window.getSelection && window.getSelection();
                if (sel && sel.removeAllRanges) {
                    try { sel.removeAllRanges(); } catch (e) {}
                }
            }

            // After rendering the text, collect word spans and their positions
            function collectWordSpans() {
                wordSpans = Array.from(document.querySelectorAll('.word'));
                wordBoundaries = [];
                let charCount = 0;
                wordSpans.forEach(span => {
                    wordBoundaries.push({
                        start: charCount,
                        end: charCount + span.textContent.length
                    });
                    charCount += span.textContent.length + 1;
                });
            }

            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.8.162/pdf.worker.min.js';
            
            async function extractTextItemsFromPdf(fileUrl) {
                const pdfDoc = await pdfjsLib.getDocument(fileUrl).promise;
                let items = [];
                for (let i = 1; i <= pdfDoc.numPages; i++) {
                    const page = await pdfDoc.getPage(i);
                    const textContent = await page.getTextContent();
                    let lastY = null;
                    let lastX = null;
                    let line = '';
                    let lineFontSizes = [];
                    textContent.items.forEach(item => {
                        const thisY = item.transform[5];
                        const thisX = item.transform[4];

                        if (lastY !== null && Math.abs(thisY - lastY) > 5) {
                            if (line.trim().length > 0) {
                                items.push({
                                    text: line.trim(),
                                    fontSize: Math.round(Math.max(...lineFontSizes))
                                });
                            }
                            line = '';
                            lineFontSizes = [];
                            lastX = null;
                        }

                        if (lastX !== null && Math.abs(thisX - lastX) > 2) {
                            line += ' ';
                        }
                        line += item.str;
                        lineFontSizes.push(item.transform[0]);
                        lastY = thisY;
                        lastX = thisX + item.width;
                    });

                    if (line.trim().length > 0) {
                        items.push({
                            text: line.trim(),
                            fontSize: Math.round(Math.max(...lineFontSizes))
                        });
                    }

                    items.push({text: '', fontSize: 0});
                }
                return items;
            }

            function renderTextItemsToHtml(items) {
                const fragment = document.createDocumentFragment();
                let wordIndex = 0;
                items.forEach(item => {
                    if (!item.text.trim()) return;
                    let className = '';
                    if (item.fontSize >= 20) {
                        className = 'pdf-title';
                    } else if (item.fontSize >= 16) {
                        className = 'pdf-subtitle';
                    } else {
                        className = 'pdf-paragraph';
                    }
                    const div = document.createElement('div');
                    div.className = className;
                    
                    item.text.split(/(\s+|[.,!?;:"'"'\-\(\)\[\]{}])/g).forEach(part => {
                        if (part.trim() === '') {
                            div.appendChild(document.createTextNode(part));
                            return;
                        }
                        if (/^\w+$/u.test(part.trim())) {
                            const span = document.createElement('span');
                            span.className = 'word';
                            span.textContent = part;
                            span.tabIndex = 0;
                            span.setAttribute('data-word-index', wordIndex++);
                            div.appendChild(span);
                        } else {
                            div.appendChild(document.createTextNode(part));
                        }
                    });
                    fragment.appendChild(div);
                });
                return fragment;
            }

            async function loadPdf(fileUrl) {
                contentDiv.innerHTML = '<div class="loading">Loading document... Please wait.</div>';
                playPauseBtn.disabled = true;
                stopBtn.disabled = true;
                highlightNarrateBtn.disabled = true;
                try {
                    const items = await extractTextItemsFromPdf(fileUrl);
                    pdfText = items.map(item => item.text).join('\n');
                    contentDiv.innerHTML = '';
                    const fragment = renderTextItemsToHtml(items);
                    contentDiv.appendChild(fragment);
                    collectWordSpans();
                    playPauseBtn.disabled = false;
                    stopBtn.disabled = false;
                    highlightNarrateBtn.disabled = false;
                } catch (e) {
                    contentDiv.innerHTML = '<div class="error">Failed to load PDF content.</div>';
                    console.error(e);
                }
            }

            loadPdf('<?php echo htmlspecialchars($file_url); ?>');

            function populateVoices() {
                voices = window.speechSynthesis.getVoices();
                // Keep the "Select Voice" option and add voices below it
                voiceSelect.innerHTML = '<option value="" selected>Select Voice</option>';
                voices.forEach((voice, i) => {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = `${voice.name} (${voice.lang})${voice.default ? ' [default]' : ''}`;
                    voiceSelect.appendChild(option);
                });
            }
            
            window.speechSynthesis.onvoiceschanged = populateVoices;
            populateVoices();

			playPauseBtn.addEventListener('click', () => {
				if (!utterance) {
					wordSpans = Array.from(document.querySelectorAll('.word'));
					// Build text from the DOM (from current starting word) to preserve punctuation and natural pauses
					let speakText = '';
					let range;
					if (wordSpans[startWordIdx]) {
						range = document.createRange();
						range.setStartBefore(wordSpans[startWordIdx]);
						range.setEndAfter(contentDiv.lastChild);
						speakText = range.toString();
					} else {
						speakText = contentDiv.innerText || contentDiv.textContent || '';
					}
					utterance = new SpeechSynthesisUtterance(speakText);
                    utterance.lang = 'en-US';
                    utterance.rate = parseFloat(speedControl.value) * 0.65;
                    // Handle case when "Select Voice" is selected (empty value)
                    const selectedVoiceIndex = voiceSelect.value;
                    const selectedVoice = selectedVoiceIndex !== '' ? voices[selectedVoiceIndex] || voices[0] : voices[0];
                    utterance.voice = selectedVoice;

					// Build boundaries of each subsequent word span within the speakText, so we can map charIndex → word
					let activeWordSpans = [];
					let activeBoundaries = [];
					if (range) {
						for (let i = startWordIdx; i < wordSpans.length; i++) {
							const wordNode = wordSpans[i];
							const wordRange = document.createRange();
							wordRange.selectNodeContents(wordNode);
							const preRange = document.createRange();
							preRange.setStart(range.startContainer, range.startOffset);
							preRange.setEnd(wordRange.startContainer, wordRange.startOffset);
							const start = preRange.toString().length;
							const end = start + wordNode.textContent.length;
							activeWordSpans.push(wordNode);
							activeBoundaries.push({ start, end });
						}
					}

					utterance.onboundary = function(event) {
						const idx = event.charIndex || 0;
						let wordIndex = -1;
						for (let i = 0; i < activeBoundaries.length; i++) {
							const b = activeBoundaries[i];
							if (idx >= b.start && idx < b.end) {
								wordIndex = i;
								break;
							}
						}
						if (wordIndex === -1) return;
						document.querySelectorAll('.word').forEach(span => span.classList.remove('tts-highlight'));
						const currentWordSpan = activeWordSpans[wordIndex];
						if (currentWordSpan) currentWordSpan.classList.add('tts-highlight');
					};
                    utterance.onend = () => {
                        playPauseBtn.textContent = '▶️ Play';
                        utterance = null;
                        clearTTSHighlight();
                        startWordIdx = 0;
                    };
                    window.speechSynthesis.speak(utterance);
                    playPauseBtn.textContent = '⏸️ Pause';
                } else if (isPaused) {
                    window.speechSynthesis.resume();
                    playPauseBtn.textContent = '⏸️ Pause';
                    isPaused = false;
                } else {
                    window.speechSynthesis.pause();
                    playPauseBtn.textContent = '▶️ Play';
                    isPaused = true;
                }
            });

            stopBtn.addEventListener('click', () => {
                if (utterance) {
                    window.speechSynthesis.cancel();
                    utterance = null;
                    playPauseBtn.textContent = '▶️ Play';
                    clearTTSHighlight();
                    startWordIdx = 0;
                }
            });

            contentDiv.addEventListener('click', async (event) => {
                const target = event.target;
                if (!target.classList.contains('word')) return;

                currentWord = target.textContent;
                popupWord.textContent = currentWord;
                popupPhonetic.textContent = 'Loading...';
                popupMeaning.textContent = 'Loading...';
                popup.style.display = 'block';
                popup.setAttribute('aria-hidden', 'false');

                const rect = target.getBoundingClientRect();
                popup.style.top = `${rect.bottom + window.scrollY + 5}px`;
                popup.style.left = `${rect.left + window.scrollX}px`;
                
                try {
                    const response = await fetch(`https://api.dictionaryapi.dev/api/v2/entries/en/${encodeURIComponent(currentWord.toLowerCase())}`);
                    if (!response.ok) throw new Error('No data found');
                    const data = await response.json();
                    const entry = data[0];
                    const syll = syllabifyWord(entry?.word || currentWord);
                    popupPhonetic.textContent = syll || (entry.phonetic || '-');
                    popupMeaning.textContent = entry.meanings[0]?.definitions[0]?.definition || 'No information found.';
                } catch (e) {
                    popupPhonetic.textContent = '-';
                    popupMeaning.textContent = 'No information found.';
                }

                if (utterance) {
                    window.speechSynthesis.cancel();
                    utterance = null;
                }
                startWordIdx = parseInt(target.getAttribute('data-word-index'));
                playPauseBtn.textContent = '▶️ Play';
                clearTTSHighlight();
            });

            document.addEventListener('click', (e) => {
                if (!popup.contains(e.target) && !e.target.classList.contains('word')) {
                    popup.style.display = 'none';
                    popup.setAttribute('aria-hidden', 'true');
                }
            });

            playBtn.addEventListener('click', () => {
                if (!currentWord) return;
                const utterance = new SpeechSynthesisUtterance(currentWord);
                utterance.lang = 'en-US';
                // Handle case when "Select Voice" is selected (empty value)
                const selectedVoiceIndex = voiceSelect.value;
                const selectedVoice = selectedVoiceIndex !== '' ? voices[selectedVoiceIndex] || voices[0] : voices[0];
                utterance.voice = selectedVoice;
                window.speechSynthesis.speak(utterance);
            });

            voiceSelect.addEventListener('change', () => {
                if (utterance) {
                    window.speechSynthesis.cancel();
                    utterance = null;
                    playPauseBtn.textContent = '▶️ Play';
                    clearTTSHighlight();
                }
            });

            speedControl.addEventListener('input', () => {
                speedValue.textContent = speedControl.value + 'x';
            });

            // Text selection functionality for narrate highlighted
            let selectedText = '';
            
            // Listen for text selection
            document.addEventListener('selectionchange', () => {
                const selection = window.getSelection();
                selectedText = selection.toString().trim();
                
                if (selectedText.length > 0) {
                    highlightNarrateBtn.disabled = false;
                    highlightNarrateBtn.textContent = `🎤 Narrate Selected (${selectedText.length} chars)`;
                } else {
                    highlightNarrateBtn.disabled = true;
                    highlightNarrateBtn.textContent = '🎤 Narrate Highlighted';
                }
            });

			// Narrate highlighted button functionality
			highlightNarrateBtn.addEventListener('click', () => {
				if (!selectedText) return;
				
				if (utterance) {
					window.speechSynthesis.cancel();
					utterance = null;
				}
				
				// Build punctuation-preserving text from the selection
				const selection = window.getSelection();
				if (!selection || selection.rangeCount === 0) return;
				const range = selection.getRangeAt(0);
				const selectionText = range.toString();
				
				// Collect selected word spans in document order
				const selectedWords = [];
				const walker = document.createTreeWalker(
					contentDiv,
					NodeFilter.SHOW_ELEMENT,
					{
						acceptNode: function(node) {
							if (node.classList && node.classList.contains('word')) {
								try {
									return range.intersectsNode(node) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_SKIP;
								} catch (e) {
									return NodeFilter.FILTER_SKIP;
								}
							}
							return NodeFilter.FILTER_SKIP;
						}
					}
				);
				let node;
				while (node = walker.nextNode()) {
					selectedWords.push(node);
				}
				
				// Compute boundaries of each selected word within the selection text
				const selectedBoundaries = [];
				selectedWords.forEach(wordNode => {
					const wordRange = document.createRange();
					wordRange.selectNodeContents(wordNode);
					const preRange = document.createRange();
					preRange.setStart(range.startContainer, range.startOffset);
					preRange.setEnd(wordRange.startContainer, wordRange.startOffset);
					const start = preRange.toString().length;
					const end = start + wordNode.textContent.length;
					selectedBoundaries.push({ start, end });
				});
				
				// Speak selection text and keep word highlighting in sync
				utterance = new SpeechSynthesisUtterance(selectionText);
				utterance.lang = 'en-US';
				utterance.rate = parseFloat(speedControl.value) * 0.85;
				
				const selectedVoiceIndex = voiceSelect.value;
				const selectedVoice = selectedVoiceIndex !== '' ? voices[selectedVoiceIndex] || voices[0] : voices[0];
				utterance.voice = selectedVoice;

                // Clear browser selection highlight right before speaking
                const selNow = window.getSelection && window.getSelection();
                if (selNow && selNow.removeAllRanges) {
                    try { selNow.removeAllRanges(); } catch (e) {}
                }
				
				utterance.onboundary = function(event) {
					const idx = event.charIndex || 0;
					let wordIndex = -1;
					for (let i = 0; i < selectedBoundaries.length; i++) {
						const b = selectedBoundaries[i];
						if (idx >= b.start && idx < b.end) {
							wordIndex = i;
							break;
						}
					}
					if (wordIndex === -1) return;
					// Clear previous and highlight current
					document.querySelectorAll('.word').forEach(span => span.classList.remove('tts-highlight'));
					const currentWordSpan = selectedWords[wordIndex];
					if (currentWordSpan) currentWordSpan.classList.add('tts-highlight');
				};
				
				utterance.onend = () => {
					utterance = null;
					playPauseBtn.textContent = '▶️ Play';
					clearTTSHighlight();
				};
				
				window.speechSynthesis.speak(utterance);
				playPauseBtn.textContent = '⏸️ Pause';
			});
        })();

            // Expose closePopup for the X button
            window.closePopup = function() {
                const p = document.getElementById('popup');
                if (p) {
                    p.style.display = 'none';
                    p.setAttribute('aria-hidden', 'true');
                }
            };

            // Close on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    window.closePopup();
                }
            });
        </script>
</body>
</html>