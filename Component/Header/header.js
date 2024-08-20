document.addEventListener("DOMContentLoaded", function() {
    const userRole = localStorage.getItem('userRole') || "GuestUser"; // Default to GuestUser if not found

    fetch("../../Component/Header/header.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("header-container").innerHTML = data;

            const headerTopic = document.querySelector(".topic");
            const abcContainer = document.querySelector(".abc");

            if (userRole === "admin") {
                headerTopic.textContent = "Admin Dashboard";
            } else if (userRole === "GuestUser") {
                headerTopic.textContent = "Trip Track";

                const loginContainer = document.createElement("div");
                loginContainer.classList.add("login-container");

                const loginButton = document.createElement("button");
                loginButton.textContent = "Login";
                loginButton.classList.add("login-button");
                loginButton.onclick = function() {
                    window.location.href = "./login.html";
                };

                loginContainer.appendChild(loginButton);
                document.querySelector(".header").appendChild(loginContainer);
            } else if (userRole === "RegisteredUser") {
                headerTopic.textContent = "Trip Track";

                const userName = localStorage.getItem('userName') || "User";
                const profilePicUrl = localStorage.getItem('profilePicUrl') || "./default-profile.png";

                const userProfileContainer = document.createElement("div");
                userProfileContainer.classList.add("user-profile-container");

                const profilePic = document.createElement("img");
                profilePic.src = profilePicUrl;
                profilePic.alt = "Profile Picture";
                profilePic.classList.add("profile-pic");

                const userNameText = document.createElement("span");
                userNameText.textContent = userName;
                userNameText.classList.add("user-name");

                userProfileContainer.appendChild(profilePic);
                userProfileContainer.appendChild(userNameText);
                document.querySelector(".header").appendChild(userProfileContainer);
            }
        })
        .catch(error => console.error("Error loading header:", error));
});
