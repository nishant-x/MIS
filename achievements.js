document.addEventListener('DOMContentLoaded', function () {
    // Sample past achievements data (replace with your actual data)
    let pastAchievements = [
      
    ];

    const achievementList = document.getElementById('achievementList');

    // Function to display past achievements
    function displayPastAchievements() {
        achievementList.innerHTML = ''; // Clear previous list

        pastAchievements.forEach(achievement => {
            const listItem = document.createElement('li');
            const dateTime = new Date(achievement.date);
            const formattedDate = dateTime.toLocaleDateString();
            const formattedTime = dateTime.toLocaleTimeString();
            listItem.innerHTML = `<strong>${achievement.title}</strong>: ${achievement.description} - ${formattedDate} ${formattedTime}`;

            // Add buttons for modifying and deleting achievements
            const modifyButton = document.createElement('button');
            modifyButton.textContent = 'Modify';
            modifyButton.addEventListener('click', function () {
                const newTitle = prompt("Enter new title:", achievement.title);
                const newDescription = prompt("Enter new description:", achievement.description);

                // Update the achievement if user provided new title and description
                if (newTitle !== null && newDescription !== null) {
                    achievement.title = newTitle;
                    achievement.description = newDescription;
                    displayPastAchievements();
                }
            });

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Delete';
            deleteButton.addEventListener('click', function () {
                const confirmDelete = confirm("Are you sure you want to delete this achievement?");
                if (confirmDelete) {
                    pastAchievements = pastAchievements.filter(item => item.id !== achievement.id);
                    displayPastAchievements();
                }
            });

            listItem.appendChild(modifyButton);
            listItem.appendChild(deleteButton);

            achievementList.appendChild(listItem);
        });
    }

    // Display past achievements when the page loads
    displayPastAchievements();

    // Form submission event listener
    document.getElementById('achievementForm').addEventListener('submit', function (event) {
        event.preventDefault();

        // Get form values
        var title = document.getElementById('achievementTitle').value;
        var description = document.getElementById('achievementDescription').value;
        var currentDate = new Date();
        var formattedDateTime = currentDate.toISOString(); // Convert to ISO format

        // Add new achievement to the list
        pastAchievements.push({ id: pastAchievements.length + 1, title: title, description: description, date: formattedDateTime });
        displayPastAchievements();

        // Clear form fields
        document.getElementById('achievementTitle').value = '';
        document.getElementById('achievementDescription').value = '';
    });
});
