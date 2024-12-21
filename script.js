const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');
const menuLinks = sidebar.querySelectorAll('a');

// Toggle sidebar on hamburger click
hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('active');

    // Toggle visibility of the hamburger icon
    if (sidebar.classList.contains('active')) {
        hamburger.style.display = 'none'; // Hide hamburger when sidebar is active
    } else {
        hamburger.style.display = 'block'; // Show hamburger when sidebar is inactive
    }
});

// Close sidebar when clicking a menu item
menuLinks.forEach(link => {
    link.addEventListener('click', () => {
        sidebar.classList.remove('active');
        hamburger.style.display = 'block'; // Show hamburger again when sidebar closes
    });
});

// Close sidebar when clicking outside of it
document.addEventListener('click', (event) => {
    if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
        sidebar.classList.remove('active');
        hamburger.style.display = 'block'; // Show hamburger again when sidebar closes
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".count");
    const speed = 200; // Adjust speed as needed

    // Function to start counter animation
    const animateCounter = (counter) => {
        const target = +counter.getAttribute("data-target");
        const increment = target / speed;

        const updateCount = () => {
            const current = +counter.innerText;
            if (current < target) {
                counter.innerText = Math.ceil(current + increment);
                setTimeout(updateCount, 10); // Adjust delay for smoother or faster animation
            } else {
                counter.innerText = target;
            }
        };
        updateCount();
    };

    // Scroll observer
    const observerOptions = {
        threshold: 0.5, // Trigger when 50% of the element is visible
    };

    const observerCallback = (entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target); // Stop observing once the animation is done
            }
        });
    };

    const observer = new IntersectionObserver(observerCallback, observerOptions);

    counters.forEach((counter) => observer.observe(counter));
});

document.addEventListener('mousemove', (e) => {
    const eye = document.querySelector('.eye');
    const pupil = document.querySelector('.pupil');
    const rect = eye.getBoundingClientRect();
    const eyeX = rect.left + rect.width / 2;
    const eyeY = rect.top + rect.height / 2;
    const angle = Math.atan2(e.clientY - eyeY, e.clientX - eyeX);

    const offsetX = Math.cos(angle) * 15; // Adjust pupil movement distance
    const offsetY = Math.sin(angle) * 15;
    pupil.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
});


// Get the button
let backToTopBtn = document.getElementById("backToTopBtn");

// Show the button when the user scrolls down 20px from the top
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        backToTopBtn.style.display = "block";
    } else {
        backToTopBtn.style.display = "none";
    }
}
// Scroll to the top of the document when the button is clicked
backToTopBtn.onclick = function() {
    window.scrollTo({top: 0, behavior: 'smooth'}); // Smooth scrolling
}

document.addEventListener('DOMContentLoaded', () => {
    // Get current time and update header message
    const chatHeaderMessage = document.getElementById('chatHeaderMessage');
    const now = new Date();
    const hours = now.getHours();

    let greeting;
    if (hours >= 5 && hours < 12) {
        greeting = "Good Morning";
    } else if (hours >= 12 && hours < 17) {
        greeting = "Good Afternoon";
    } else if (hours >= 17 && hours < 21) {
        greeting = "Good Evening";
    } else {
        greeting = "Good Night";
    }

    chatHeaderMessage.textContent = `✨ ${greeting}`;
});

document.addEventListener('DOMContentLoaded', () => {
    const chatbotButton = document.getElementById('chatbotButton');
    const chatSidebar = document.querySelector('.chat-sidebar');
    const closeChat = document.getElementById('closeChat');
    const sendMessage = document.getElementById('sendMessage');
    const chatInput = document.getElementById('chatInput');
    const messagesContainer = document.querySelector('.messages');

    // Open and Close Chat Sidebar
    chatbotButton.addEventListener('click', () => {
        chatSidebar.classList.add('active');
    });

    closeChat.addEventListener('click', () => {
        chatSidebar.classList.remove('active');
    });

    const keywordResponses = [
        { keywords: [/timings/i], response: "The library is open from 🕘 9:00 AM to 5:15 PM , Monday to Saturday, and 🕗 08:00 AM to 12:00 Midnight during Examination." },
        { keywords: [/collect|borrow|renew|issue\s*book/i], response: "You can issue/renew a book by visiting the librarian's desk or through the <a href='course_books.php'>online portal</a>. 📔" },
        { keywords: [/return/i], response: "To return a book, please visit the library's return counter." },
        { keywords: [/membership/i], response: "You can register for library membership online or at the librarian's desk." },
        { keywords: [/contact/i], response: "You can contact us at <a href='mailto:principal@bldeacet.ac.in'>mailto:principal@bldeacet.ac.in</a> or <a href='tel:08352261120'>tel:08352261120</a>." },
        { keywords: [/rules/i], response: "Library rules include maintaining silence, no food or drinks, and returning books on time." },
        { keywords: [/services/i], response: "Our services include book borrowing, research assistance, and free internet access." },
        { keywords: [/thank|thanks/i], response: "You're welcome! 😊 I'm here to help anytime you need. Let me know if there's anything else I can assist you with!" },
        { keywords: [/bye|goodbye/i], response: "See you later! Keep shining bright. ✨" },
        { keywords: [/joke/i], response: "Why did the library hire a cat?<br>Because it was good at <em>purrsonal</em> research! 🐱📚" },
        { keywords: [/bad|hate|idiot|stupid|useless/i], response: "🥺 SORRY if I did something wrong, I'm trying my best to improve." },
        { keywords: [/hi|hello|hey|yo/i], response: "👋 Hello Bibliophile! How can I assist you today?" },
        { keywords: [/recommend/i], response: "I can recommend a variety of books! What genre are you interested in?" },
        { keywords: [/search/i], response: "You can search for books by title, author, or ISBN on the <a href='course_books.php'>online portal</a>. 🔍" },
        { keywords: [/107/i], response: "<p> <bold style='font-size: 5em'>&#128150;</bold><br>One is loved because one is loved. No reason is needed for loving.</p>"},
        { keywords: [/late|overdue|fine/i], response: "If your book is overdue, you can pay the fine at the librarian's desk or via the online portal." },
        { keywords: [/computer|internet|wifi/i], response: "We offer free internet access and computer usage for library members." },
        { keywords: [/events|workshops|seminars/i], response: "We regularly host workshops and seminars. Check the announcements on our notice board or website for upcoming events." },
        { keywords: [/ebook|digital|online/i], response: "You can access ebooks through our digital library portal. 📱" },
        { keywords: [/lost|misplaced/i], response: "If you've lost a book, please report it to the librarian immediately. We may charge a replacement fee." },
        { keywords: [/feedback|suggestions/i], response: "We value your feedback! You can submit suggestions at the library counter or via our <a href='contact.html'>website</a>." },
        { keywords: [/holiday|closed/i], response: "The library will be closed on public holidays. Please check the website for updates." },
        { keywords: [/study|space|room/i], response: "You can book a study room or space at the library counter or online." },
        { keywords: [/new\s*arrivals|recent\s*books/i], response: "Check out the latest additions to our collection in the 'New Arrivals' section on our website!" },
        { keywords: [/membership\s*card|registration/i], response: "Sign up for a membership card at the library's registration desk or via the <a href='register.php'>online portal</a>. 🏷️" },
        { keywords: [/librarian|staff/i], response: "Our librarian and staff are always available to assist you with finding resources and research help. 👨‍🏫" },
        { keywords: [/catalogue|search\s*books/i], response: "You can browse our full catalogue online or at the library for the books you're looking for. 📚" },
        { keywords: [/donate|donations/i], response: "We welcome book donations! Please contact the library staff if you'd like to donate books." },
        { keywords: [/book\s*club|reading\s*group/i], response: "Join our Book Club! Check with the library staff for more details on upcoming reading groups." },
        { keywords: [/audio|podcasts|audiobooks/i], response: "We offer a selection of audiobooks and podcasts. Visit the library's audio section for more information." },
        { keywords: [/printing|photocopy|scanning/i], response: "You can print, photocopy, or scan documents at the library's designated service desk." },
        { keywords: [/library\s*card|ID/i], response: "You need a valid library card or student ID to borrow books and access library services." },
        { keywords: [/hours|open|close/i], response: "The library is open from 9 AM to 5:15 PM. We close for lunch from 1:15 PM to 2:15 PM." },
        { keywords: [/book\s*extension|renewal/i], response: "You can extend your book borrowing period by visiting the library or online through your account." },
        { keywords: [/access|restricted|special\s*collection/i], response: "Some books are part of our restricted collection and can only be accessed with special permission from the librarian." }
    ];

    // Preprocess user input
    function preprocessInput(input) {
        return input.toLowerCase().replace(/[^\w\s]/g, "").trim();
    }

    // Get Response Based on Keywords
    function getResponse(userInput) {
        const processedInput = preprocessInput(userInput);
        
        for (let entry of keywordResponses) {
            for (let keyword of entry.keywords) {
                if (processedInput.match(keyword)) {
                    return entry.response;
                }
            }
        }
        return "Sorry, I didn't understand that. Could you please clarify?";
    }

    // Handle User Message
    const handleUserMessage = () => {
        const userMessage = chatInput.value.trim();

        if (!userMessage) return;

        // Display user message
        appendMessage('user', userMessage);

        // Get bot response based on keywords
        const botResponse = getResponse(userMessage);
        setTimeout(() => appendMessage('bot', botResponse), 500);

        // Clear input
        chatInput.value = '';
    };

// Append message to chat
const appendMessage = (sender, message) => {
    const messageElement = document.createElement('div');
    messageElement.classList.add(sender === 'user' ? 'user-message' : 'bot-message');
    
    // Use innerHTML to allow rendering of HTML content (like <a> tags)
    messageElement.innerHTML = message;
    messagesContainer.appendChild(messageElement);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
};


    // Send Message Event
    sendMessage.addEventListener('click', handleUserMessage);
    chatInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') handleUserMessage();
    });
});


document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default anchor behavior
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);

        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
            // Update URL without adding to history
            history.replaceState(null, null, `#${targetId}`);
        }
    });
});