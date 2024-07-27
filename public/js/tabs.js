function openTab(tabNumber) {
    let btnId=""
    let tabId=""
    if (tabNumber == 0) {
        btnId = "btnIntro"
        tabId = "tabIntro"
    }
    else if (tabNumber == maxNumberOfRounds+1) {
        btnId = "btnRules"
        tabId = "tabRules"
    }
    else {
        btnId = "btnRound"+tabNumber
        tabId = "tabRound"+tabNumber
    }
  
    // Get all elements with class="tabcontent" and hide them
    let tabcontent = document.getElementsByClassName("tabcontent");
    for (let i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
  
    // Get all elements with class="tablinks" and remove the class "active"
    let tablinks = document.getElementsByClassName("tablinks");
    for (let i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
  
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabId).style.display = "block";
    let elem = document.getElementById(btnId)
    elem.className += " active";

    if (tabNumber > 0 && tabNumber <= maxNumberOfRounds) {
        ShowRound(tabNumber)
        let fields = document.getElementsByClassName("Round"+tabNumber+"Field")
        let first = FirstEmptyField(fields)
        first.scrollIntoView({ block: "center" })
        first.focus()
    }
    SetCookie("TabNumber", tabNumber)
} 