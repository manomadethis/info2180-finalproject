window.addEventListener('load', () => {
    // Input fields
    const fnameInput = document.querySelector("#firstname");
    const lnameInput = document.querySelector("#lastname");
    const emailInput = document.querySelector("#emailaddr");
    const passwordInput = document.querySelector("#password");

    // Dropdown elements
    const drop_Select = document.querySelector('.select');
    const drop_Caret = document.querySelector('.caret');
    const drop_Menu = document.querySelector('.menu');
    const drop_Options = document.querySelectorAll('.menu li');
    const drop_Selected = document.querySelector('.selected');

    // Other elements
    const saveBtn = document.querySelector("button");
    const controls = document.querySelector(".controls");
    const msgBox = document.querySelector(".msg");

    // Save button click event
    saveBtn.addEventListener('click', (e) => {
        e.preventDefault();
        let fieldsOK = true;

        // Validate first name
        const fnameMsg = document.querySelector(".fnameMsg");
        if (fnameInput.value.trim() == "") {
            fieldsOK = false;
            fnameMsg.innerHTML = '<i class="material-icons">&#xe000;</i>Enter a First Name';
            fnameMsg.classList.add('error');
        } else {
            fnameMsg.innerHTML = '';
            fnameMsg.classList.remove('error');
        }

        // Validate last name
        const lnameMsg = document.querySelector(".lnameMsg");
        if (lnameInput.value.trim() == "") {
            fieldsOK = false;
            lnameMsg.innerHTML = '<i class="material-icons">&#xe000;</i>Enter a Last Name';
            lnameMsg.classList.add('error');
        } else {
            lnameMsg.innerHTML = '';
            lnameMsg.classList.remove('error');
        }

        // Validate email
        const emailMsg = document.querySelector(".emailMsg");
        let mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!emailInput.value.trim().match(mailformat)) {
            fieldsOK = false;
            emailMsg.innerHTML = '<i class="material-icons">&#xe000;</i>Enter a valid email';
            emailMsg.classList.add('error');
        } else {
            emailMsg.innerHTML = '';
            emailMsg.classList.remove('error');
        }

        // Validate password
        const passwordMsg = document.querySelector(".passwordMsg");
        if (passwordInput.value.trim() == "") {
            fieldsOK = false;
            passwordMsg.innerHTML = '<i class="material-icons">&#xe000;</i>Enter a password';
            passwordMsg.classList.add('error');
        } else {
            passwordMsg.innerHTML = '';
            passwordMsg.classList.remove('error');
        }

        // If all fields are OK, create new user
        if (fieldsOK) {
            msgBox.textContent = "Creating New User...";
            controls.classList.remove('fail');
            controls.classList.add('success');

            fetch('php/addUser.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `fname=${fnameInput.value.trim()}&lname=${lnameInput.value.trim()}&email=${emailInput.value.trim()}&password=${passwordInput.value.trim()}&role=${drop_Selected.textContent}`
            })
                .then(response => {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw new Error('Something was wrong with fetch request!');
                    }
                })
                .then(data => {
                    console.log(data);
                    // If user was added successfully, redirect to index.php
                    window.location.href = 'index.php';
                })
                .catch(error => {
                    console.log(`ERROR: ${error}`);
                    msgBox.textContent = "Couldn't create new user";
                    controls.classList.add('fail');
                    controls.classList.remove('success');
                });
        } else {
            msgBox.textContent = "Couldn't create new user";
            controls.classList.add('fail');
            controls.classList.remove('success');
        }
    });

    // Dropdown click event
    drop_Select.addEventListener('click', () => {
        drop_Select.classList.toggle('select-clicked');
        drop_Caret.classList.toggle('caret-rotate');
        drop_Menu.classList.toggle('menu-open');
    });

    // Dropdown option click event
    drop_Options.forEach(option => {
        option.addEventListener('click', () => {
            drop_Selected.textContent = option.textContent;
            drop_Select.classList.remove('select-clicked');
            drop_Caret.classList.remove('carer-rotate');
            drop_Menu.classList.remove('menu-open');

            drop_Options.forEach(option => {
                option.classList.remove('active');
            });

            option.classList.add('active');
        });
    });
});