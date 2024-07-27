function RoundFieldParseValues(values) {
    let result = []
    let nums = values.split(",")
    nums.forEach((num) => {
        if (num != "" && !isNaN(num)) {
            result.push(num)
        }
    })
    return result
}

function RoundGetNextValue(value, values, plus) {
    if (plus) {
        for (let i=0; i < values.length; i++) {
            if (values[i] > value)
                return values[i]
        }
        return value
    }
    else {
        for (let i=values.length-1; i >= 0; i--) {
            if (values[i] < value)
                return values[i]
        }
        return value
    }
}

function adjustNumber(id, plus) {
    let elem = document.getElementById(id)

    let min = elem.getAttribute("min");
    if (isNaN(min)) return
    min = parseInt(min)

    let max = elem.getAttribute("max");
    if (isNaN(max)) return
    max = parseInt(max)
    if (max <= min) return
    
    let step = elem.getAttribute("step");
    if (isNaN(step)) return
    step = parseInt(step)
    if (step < 1) return
    
    let values = elem.getAttribute("values");
    values = RoundFieldParseValues(values)

    let value = cleanNumber(elem.value, min, max, step)

    if (values.length)
        value = RoundGetNextValue(value, values, plus)
    else {
        if (plus)
            value += step
        else
            if (elem.value == "")
                value = 0
            else
                value -= step
    }

    if (value < min)
        value = min
    if (value > max)
        value = max

    elem.value = parseInt(value)

    let event = new Event('change');
    elem.dispatchEvent(event);    
}

function AddRoundListeners(elem)
{
    let edtValueId = elem.id
    let edtValue = elem
    if (edtValue.tagName == "SELECT")
        return

    let edtMinusId = edtValueId+"_Minus"
    let edtMinus = document.getElementById(edtMinusId)
    edtMinus.addEventListener("click", (event) => {
        let edtId = event.currentTarget.id.replace("_Minus",'');
        adjustNumber(edtId, false)
    });

    let edtPlusId = edtValueId+"_Plus"
    let edtPlus = document.getElementById(edtPlusId)
    edtPlus.addEventListener("click", (event) => {
        let edtId = event.currentTarget.id.replace("_Plus",'');
        adjustNumber(edtId, true)
    });

    let exactlyOne = elem.getAttribute("exactlyOne")
    if (exactlyOne) {
        edtValue.addEventListener("change", (event) => {
            let edtTarget = event.currentTarget
            let roundNumber = edtTarget.getAttribute("RoundNumber")
            let teamNumber = edtTarget.getAttribute("TeamNumber")
            let value = parseInt(edtTarget.value)
            let edtTargetBaseId = event.currentTarget.id.split("_")[0]
            if (value > 0) {
                for (let i = 1; i <= maxNumberOfTeams; i++) {
                    if (i != teamNumber) {
                        let edtOtherTeamId = RoundFieldId(edtTargetBaseId, i, roundNumber)
                        let edtOtherTeam = document.getElementById(edtOtherTeamId)
                        edtOtherTeam.value = "0"
                    }
                }
            }
        });
    }
    let min = partiallyCleanNumber(elem.getAttribute("min"))
    let max = partiallyCleanNumber(elem.getAttribute("max"))   
    if (min < 0 &&
        max <= 0) {
        elem.addEventListener("change", (event) => {
            let val = parseInt(event.currentTarget.value)
            if (val > 0) {
                val = -val
                event.currentTarget.value = val
            }
        });    
    }
}

function ShowRound(roundNumber) {
    if (roundNumber == 0)
        return
    let teamNames = GetTeamNames()
    let numTeams = GetNumberOfTeams()
    for (let i = 1; i <= maxNumberOfTeams; i++) {
        let roundTeamNameElem = document.getElementById("Round"+roundNumber+"Team"+i)
        roundTeamNameElem.textContent = teamNames[i-1]
        let roundTeamNameDivElem = document.getElementById("Round"+roundNumber+"Team"+i+"Div")
        let roundTotalColElem = document.getElementById("Total_"+i+"_"+roundNumber+"_Col")

        let prevTotalColElems = []
        for (let prevRoundNum=roundNumber-1; prevRoundNum >= 1; prevRoundNum--) {
            prevTotalColElems.push(document.getElementById("Total_"+i+"_"+roundNumber+"_"+prevRoundNum+"_Col"))
        }

        let roundGrandTotalColElem = document.getElementById("GrandTotal_"+i+"_"+roundNumber+"_Col")

        if (i <= numTeams) {
            ShowElement(roundTeamNameDivElem)
            let rowNum = 1
            while(true) {
                let colElem = document.getElementById(rowNum+"_"+i+"_"+roundNumber+"_Col")
                if (!colElem)
                    break
                ShowElement(colElem)
        
                rowNum++
            }
            ShowElement(roundTotalColElem)
            prevTotalColElems.forEach((prevElem) => {
                ShowElement(prevElem)
            })
            if (roundNumber > 1 && roundNumber < maxNumberOfRounds)
                ShowElement(roundGrandTotalColElem)
        }
        else {
            HideElement(roundTeamNameDivElem)
            let rowNum = 1
            while(true) {
                let colElem = document.getElementById(rowNum+"_"+i+"_"+roundNumber+"_Col")
                if (!colElem)
                    break
                HideElement(colElem)

                rowNum++
            }
            HideElement(roundTotalColElem)
            prevTotalColElems.forEach((prevElem) => {
                HideElement(prevElem)
            })
            if (roundNumber > 1 && roundNumber < maxNumberOfRounds)
                HideElement(roundGrandTotalColElem)
        }
    }
}
