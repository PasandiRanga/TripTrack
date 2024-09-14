document.addEventListener("DOMContentLoaded", function() {
    const userRole = localStorage.getItem('userRole') || "GuestUser"; // Default to GuestUser if not found

    fetch("../../Component/Header/header.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("header-container").innerHTML = data;

            const headerTopic = document.querySelector(".topic");
            const abcContainer = document.querySelector(".abc");

            if (userRole === "Admin") {
                headerTopic.textContent = "Admin Dashboard";

                const adminName = localStorage.getItem('adminName') || "Admin";
                const adminProfilePicUrl = localStorage.getItem('adminProfilePicUrl') || "./default-profile.png";

                const adminProfileContainer = document.createElement("div");
                adminProfileContainer.classList.add("admin-profile-container");

                const profilePic = document.createElement("img");
                profilePic.src = adminProfilePicUrl;
                profilePic.alt = "Profile Picture";
                profilePic.classList.add("profile-pic");

                const adminNameText = document.createElement("span");
                adminNameText.textContent = adminName;
                adminNameText.classList.add("admin-name");

                adminProfileContainer.appendChild(profilePic);
                adminProfileContainer.appendChild(adminNameText);
                document.querySelector(".header").appendChild(adminProfileContainer);

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
