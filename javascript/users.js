// Path: javascript/users.js
// Handle search bar
const searchBar = document.querySelector(".search input"),
  searchIcon = document.querySelector(".search button"),
  usersList = document.querySelector(".users-list"),
  createGroupButton = document.getElementById('create-group');

searchIcon.onclick = () => {
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if (searchBar.classList.contains("active")) {
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
}

searchBar.onkeyup = () => {
  let searchTerm = searchBar.value;
  if (searchTerm != "") {
    searchBar.classList.add("active");
  } else {
    searchBar.classList.remove("active");
  }
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/search.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        usersList.innerHTML = data;
      }
    }
  }
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("searchTerm=" + searchTerm);
}

setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/users.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (!searchBar.classList.contains("active")) {
          usersList.innerHTML = data;
        }
      }
    }
  }
  xhr.send();
}, 500);

// Handle group creation
createGroupButton.addEventListener('click', () => {
  const groupName = prompt('Enter the group name:');
  if (groupName) {
    fetch('php/create-group.php', {
      method: 'POST',
      body: JSON.stringify({ group_name: groupName }),
      headers: {
        'Content-Type': 'application/json',
      },
    })
      .then(response => response.text())
      .then(data => {
        alert(data);
        // Refresh the user list or take other actions as needed
        refreshUserList();
      })
      .catch(error => {
        console.error('Error creating group:', error);
      });
  }
});

// Handle group joining (You can add this part as needed)
// Create a function to join a group and call it when necessary
function joinGroup(groupId) {
  // Send an AJAX request to php/join-group.php with the groupId
  fetch('php/join-group.php', {
    method: 'POST',
    body: JSON.stringify({ group_id: groupId }),
    headers: {
      'Content-Type': 'application/json',
    },
  })
    .then(response => response.text())
    .then(data => {
      alert(data);
      // Refresh the user list or take other actions as needed
      refreshUserList();
    })
    .catch(error => {
      console.error('Error joining group:', error);
    });
}

// Helper function to refresh the user list
function refreshUserList() {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/users.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (!searchBar.classList.contains("active")) {
          usersList.innerHTML = data;
        }
      }
    }
  }
  xhr.send();
}
