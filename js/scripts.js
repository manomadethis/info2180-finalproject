function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

window.onload = function() {
    var url = new URL(window.location.href);
    var tab = url.searchParams.get("tab");
    if (tab) {
        var evt = {
            currentTarget: document.querySelector('.tablink.' + tab)
        };
        openTab(evt, tab);
    }
}

document.querySelectorAll('#dashboard-filter a').forEach(function(filter) {
    filter.addEventListener('click', function(event) {
        event.preventDefault();
        var selected = document.querySelector('#dashboard-filter a.selected');
        if (selected) {
            selected.classList.remove('selected');
        }
        this.classList.add('selected');
    });
});