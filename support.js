function confirmDelete(courseid) {
    var c = confirm("Are you sure you want to delete this entry?");
    if (c) {
        window.location.href = "deleteCourse.php?courseid=" + courseid;
    }
}

function confirmAdd(courseid) {
    var c = confirm("Are you sure you want to delete this entry?");
    if (c) {
        window.location.href = "deleteCourse.php?courseid=" + courseid;
    }
}
