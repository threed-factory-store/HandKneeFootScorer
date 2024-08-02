const maxNumberOfTeams  = 3
const maxNumberOfRounds = 4
  
function GetNumberOfTeams() {
    let result = 0
    let numTeams2Elem = document.getElementById("introNumTeams2")
    if (numTeams2Elem.checked)
        result = 2
    let numTeams3Elem = document.getElementById("introNumTeams3")
    if (numTeams3Elem.checked)
        result = 3
    return result
}

function GetTeamNames() {
    let result = []

    for (i = 1; i <= maxNumberOfTeams; i++) {
        result[i-1] = document.getElementById("introTeamName"+i).value
    }

    return result
}

function GetTeamName(teamNumber) {
    let names = GetTeamNames()
    return names[teamNumber-1]
}

function ShowElementById(id) {
    let elem = document.getElementById(id)
    ShowElement(elem)
}

function ShowElement(elem) {
    elem.classList.remove("d-none")
}

function HideElementById(id) {
    let elem = document.getElementById(id)
    HideElement(elem)
}

function HideElement(elem) {
    elem.classList.add("d-none")
}

const cookiePrefix="HKF_"
function GetCookie(name, defaultValue="") {
    let cname = cookiePrefix+name + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i <ca.length; i++) {
      let c = ca[i].trim();
      if (c.indexOf(cname) == 0) {
        return c.substring(cname.length, c.length);
      }
    }
    return defaultValue;
}

function SetCookie(name, value) {
    document.cookie = cookiePrefix+name+"="+value+"; SameSite=Strict"
}

function ClearCookies() {
    document.cookie.split(';').forEach(cookie => {
        const eqPos = cookie.indexOf('=');
        const name = eqPos > -1 ? cookie.substring(0, eqPos).trim() : cookie.trim();
        if (name.startsWith(cookiePrefix))
            document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT';
    });
}

class BreakError extends Error {
    constructor(message, elem) {
        super(message);
        this.elem = elem;
    }
}

function RoundFieldId(baseId, teamNumber, roundNumber) {
    return baseId+"_"+teamNumber+"_"+roundNumber
}

function partiallyCleanNumber(value) {
    let result = parseInt(value)
    if (isNaN(result)) result = 0
    return result
}

function cleanNumber(value, min, max, step) {
    let result = partiallyCleanNumber(value)
    if (result < min) result = min
    if (result > max) result = max
    result = Math.floor(result/step) * step
    return result
}

function GetRoundTotals(roundNumber, teamNum, ignoreErrors) {
    let result = 0
    fields = document.getElementsByClassName("Round"+roundNumber+"Team"+teamNum+"Field")
    Array.from(fields).forEach((elem) => {
        let rowNumber = elem.getAttribute("RowNumber")
        let rowElem = document.getElementById("Round"+roundNumber+"Row"+rowNumber)
        if (!rowElem.classList.contains("d-none")) {
            let val = elem.value.trim()
            val = parseInt(val)
            if (isNaN(val) && !ignoreErrors)
                throw new BreakError("", elem)

            var min = elem.getAttribute("min");
            var max = elem.getAttribute("max");
            var step = elem.getAttribute("step");
            val = cleanNumber(val, min, max, step)
            if (val != elem.value && step && !ignoreErrors) {
                let fieldLabel = elem.getAttribute("FieldLabel")    
                throw new BreakError(fieldLabel+" only accepts multiples of "+step, elem)
            }
    
            result += parseInt(elem.value) || 0
        }
    });
    return result
}

function GetGrandTotals(roundNumber, teamNum, ignoreErrors) {
    let result = 0
    for (let i=1; i <= roundNumber; i++) {
        result += GetRoundTotals(i, teamNum, ignoreErrors)
    }
    return result
}

function GetGameTotals() {
    let totals = []
    for (let i=1; i <= GetNumberOfTeams(); i++) {
        let total = GetGrandTotals(maxNumberOfRounds, i, false)
        totals[i] = {team: i, total: total}
    }
    totals.sort((a, b) => b.total - a.total)
    return totals
}

// function GetUniqueExactlyOneNames(fields) {
//     result = []
//     Array.from(fields).forEach((elem) => {
//         let name = elem.getAttribute("exactlyOne")
//         if (name) {
//             let msg = elem.getAttribute("exactlyOneMsg")
//             result[name] = {
//                 msg: msg
//             }
//         }
//     })
//     return result
// }

// function CheckExactlyOnes(roundNumber, ignoreErrors) {
//     if (ignoreErrors)
//         return

//     let fields = document.getElementsByClassName("Round"+roundNumber+"Field")
//     let names = GetUniqueExactlyOneNames(fields)
//     for (var name in names) {
//         let firstElem = null
//         let msg = names[name].msg

//         foundOne = false
//         Array.from(fields).forEach((field) => {
//             fieldExactlyOneName = field.getAttribute("exactlyOne")
//             if (fieldExactlyOneName == name) {
//                 if (!firstElem)
//                     firstElem = field
//                 fieldValue = parseInt(field.value)
//                 if (fieldValue != 0) {
//                     if (foundOne)
//                         throw new BreakError(msg, firstElem)
//                     foundOne = true
//                 }
//             }
//         })
//         if (!foundOne)
//             throw new BreakError(msg, firstElem)
//     }
// }

// function CheckBonusForGoingOut(roundNumber, ignoreErrors) {
//     if (ignoreErrors)
//         return

//     numTeams = GetNumberOfTeams()
//     firstElem = null
//     for (let teamNum = 1; teamNum <= numTeams; teamNum++) {
//         id = RoundFieldId("edtBonusForGoingOut", teamNum, roundNumber)
//         elem = document.getElementById(id)
//         if (teamNum == 1)
//             firstElem = elem
//         var min = elem.getAttribute("min");
//         var max = elem.getAttribute("max");
//         var step = elem.getAttribute("step");

//         val = cleanNumber(elem.value, min, max, step)
//         if (val != 0)
//             return
//     }
//     throw new BreakError("One team should have a Bonus For Going Out.", firstElem)
// }

// function GetUniqueAtLeastOneNames(fields) {
//     result = []
//     Array.from(fields).forEach((elem) => {
//         let name = elem.getAttribute("atLeastOne")
//         if (name) {
//             let val = parseInt(elem.getAttribute("atLeastOneValue"))
//             let msg = elem.getAttribute("atLeastOneMsg")
//             result[name] = {
//                 value: val,
//                 msg: msg
//             }
//         }
//     })
//     return result
// }

// class FoundAtLeastOneNotAnError extends Error {
// }

// function CheckAtLeastOnes(roundNumber, ignoreErrors) {
//     if (ignoreErrors)
//         return

//     let fields = document.getElementsByClassName("Round"+roundNumber+"Field")
//     let names = GetUniqueAtLeastOneNames(fields)
//     for (var name in names) {
//         let firstElem = null
//         let value = names[name].value
//         let msg = names[name].msg

//         try {
//             Array.from(fields).forEach((field) => {
//                 fieldAtLeastOneName = field.getAttribute("atLeastOne")
//                 if (fieldAtLeastOneName == name) {
//                     if (!firstElem)
//                         firstElem = field
//                     fieldValue = parseInt(field.value)
//                     if (fieldValue == value)
//                         throw new FoundAtLeastOneNotAnError()    
//                 }
//             })
//             throw new BreakError(msg, firstElem)
//         }
//         catch (e) {
//             if (!(e instanceof FoundAtLeastOneNotAnError)) throw e;
//         }
//     }
// }

  
function UpdateRoundTotals(roundNumber, ignoreErrors, wereInTheRound) {
    let result = true
    let alertedOnce = false
    let numTeams = GetNumberOfTeams()
    for (let teamNum = 1; teamNum <= numTeams; teamNum++) {
        let totalElem = document.getElementById("Total_"+teamNum+"_"+roundNumber)
        let prevTotalElems = []
        for (let x=1; x < roundNumber; x++) {
            prevTotalElems[x] = document.getElementById("Total_"+teamNum+"_"+roundNumber+"_"+x)
        }
        
        try {
            let total = GetRoundTotals(roundNumber, teamNum, ignoreErrors)

            // The "save..." variables and setting roundNumber inside the loop are
            // in case we throw an Error in GetRoundTotals
            let saveWereInTheRound = wereInTheRound
            let saveRoundNumber = roundNumber
            wereInTheRound = false
            for (let x=1; x < saveRoundNumber; x++) {
                roundNumber = x
                prevTotalElems[x].innerText = GetRoundTotals(x, teamNum, ignoreErrors)
            }
            wereInTheRound = saveWereInTheRound
            roundNumber = saveRoundNumber
            totalElem.innerText = total

            if (roundNumber > 1 && roundNumber < maxNumberOfRounds) {
                let grandTotal = GetGrandTotals(roundNumber, teamNum, ignoreErrors)
                document.getElementById("GrandTotal_"+teamNum+"_"+roundNumber).innerText = grandTotal
            }    
        } catch (e) {
            let result = false
            if (!(e instanceof BreakError)) throw e;
            let fieldLabel = e.elem.getAttribute("FieldLabel")
            
            let inRound = ""
            if (!wereInTheRound)
                inRound = " in Round "+roundNumber
            
            let msg = ""
            if (e.message)
                msg = e.message+inRound
            else
                msg = "Please fix '"+fieldLabel+"' for "+GetTeamName(teamNum)+inRound

            if (!alertedOnce) {
                alert(msg)
                alertedOnce = true

                openTab(roundNumber)
                e.elem.scrollIntoView({ block: "center" })
                e.elem.focus()
            }
            totalElem.value = ""
            return result
        }
    }
    // If we're not ignoring errors, then we're "Done with Round X".
    // If we made it here, there are no errors.  So go to the next Round.
    if (!ignoreErrors){
        if (roundNumber < maxNumberOfRounds) {
            openTab(roundNumber+1)
        }
        else {
            if (!alertedOnce) {
                ShowTheResults()
            }
        }
    }
    return result
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function ShowTheResults() {
    let numTeams = GetNumberOfTeams()
    let teamNames = GetTeamNames()
    let totals = GetGameTotals()
    let html = ""
    let hStart = "<h4>"
    let hEnd = "</h4>"
    for (let i = 0; i < numTeams; i++) {
        html += "<div class='row mb-3'>\n \
                    <div class='col'> \n \
                    </div> \n \
                    <div class='col'>\n \
                        "+hStart+teamNames[totals[i].team-1]+hEnd+" \n \
                    </div> \n \
                    <div class='col text-end'> \n \
                        "+hStart+numberWithCommas(totals[i].total)+hEnd+" \n \
                    </div> \n \
                    <div class='col'> \n \
                    </div> \n \
                </div>"
        hStart = ""
        hEnd = ""
    }

    let modalBodyElem = document.getElementById("resultsModalBody")
    modalBodyElem.innerHTML = html
    
    let modalElem = document.getElementById("resultsModal")
    modalElem.style.display = "block";

}

function UpdateTotals(ignoreErrors) {
    for (let i = 1; i <= maxNumberOfRounds; i++)
        if (!UpdateRoundTotals(i, ignoreErrors, false))
            break
}

function ClearFields() {
    for (let roundNumber = 1; roundNumber <= maxNumberOfRounds; roundNumber++) {
        for (let teamNum = 1; teamNum <= maxNumberOfTeams; teamNum++) {
            let fields = document.getElementsByClassName("Round"+roundNumber+"Team"+teamNum+"Field")
            Array.from(fields).forEach((elem) => {
                elem.value = ""
            });
        }
    }
}

function NewGame() {
    if (confirm("This will erase all your hard effort!")) {
        // ClearCookies()
        ClearFields()
        for (let roundNumber = 1; roundNumber <= maxNumberOfRounds; roundNumber++) {
            UpdateRoundTotals(roundNumber, true, false)
            // let btnRound = document.getElementById("btnRound"+roundNumber)
            // btnRound.classList.add("RoundDisabled")
        }
        openTab(0)
    }
}

function FirstEmptyField(fields) {
    let result = fields[0]
    for (let i = 0; i < fields.length; i++) {
        if (fields[i].value.trim() == "") {
            result = fields[i]
            break
        }
    }
    return result
}

function CurrentRoundNumber() {
    for (let i = 1; i <= maxNumberOfRounds; i++) {
        let btnRound = document.getElementById("btnRound"+i)
        let roundNum = btnRound.getAttribute("RoundNumber")
        if (btnRound.classList.contains("active"))
            return i
    }
    return 0
}