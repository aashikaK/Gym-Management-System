document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchbox");
    const suggestionsBox = document.getElementById("suggestions-box");

    const pageRedirects = {
        "What services do you offer?": "services.html",
        "Do you have personal trainers?": "services.html",
        "What membership plans are available?": "services.html",
        "Do you offer online workout plans?": "services.html",
        "Where is your gym located?": "contactus.html",
        "What are your opening hours?": "contactus.html",
        "Can I bring a guest?": "contactus.html",
        "Do you sell gym equipment?": "add-facilities.html",
        "Can I rent gym equipment?": "add-facilities.html",
        "Do you offer protein supplements?": "add-facilities.html",
        "What diet plans do you provide?": "services.html",
        "What group classes do you have?": "services.html",
        "Do you have a Zumba or dance program?": "services.html",
        "What is your vision and mission?": "about.html",
        "Why should I choose ShapeShifter Fitness?": "about.html",
        "What facilities do you offer?": "add-facilities.html",
        "What are your gym rules?": "contactus.html",
        "How can I contact you?": "contactus.html"
    };

    const questions = Object.keys(pageRedirects);

    searchInput.addEventListener("focus", showSuggestions);
    
    //  Also show suggestions when typing
    searchInput.addEventListener("input", showSuggestions);

    function showSuggestions() {
        let inputValue = searchInput.value.toLowerCase();
        suggestionsBox.innerHTML = "";

        let filteredQuestions = questions.filter(q => q.toLowerCase().includes(inputValue));

        if (filteredQuestions.length > 0) {
            suggestionsBox.style.display = "block";

            filteredQuestions.forEach(q => {
                let suggestionItem = document.createElement("div");
                suggestionItem.textContent = q;
                suggestionItem.classList.add("suggestion-item");

                suggestionItem.addEventListener("click", () => {
                    searchInput.value = q;
                    suggestionsBox.style.display = "none";
                    window.location.href = pageRedirects[q]; // Redirects on click
                });

                suggestionsBox.appendChild(suggestionItem);
            });
        } else {
            suggestionsBox.style.display = "none";
        }
    }

    // Hide suggestions when clicking outside
    document.addEventListener("click", (event) => {
        if (!searchInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
            suggestionsBox.style.display = "none";
        }
    });

    //  Handle form submission when pressing Enter
    document.querySelector(".search").addEventListener("submit", (event) => {
        event.preventDefault();
        let inputValue = searchInput.value.toLowerCase().trim();
        let matchedPage = null;

        for (let question in pageRedirects) {
            if (inputValue.includes(question.toLowerCase())) {
                matchedPage = pageRedirects[question];
                break;
            }
        }

        if (matchedPage) {
            window.location.href = matchedPage;
        } else {
            alert("No exact match found. Redirecting to Contact Us. You can ask us your query there!");
            window.location.href = "contactus.html";
        }
    });
});
