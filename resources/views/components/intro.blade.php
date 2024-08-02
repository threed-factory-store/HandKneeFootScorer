<p><h3>Hand (Knee) and Foot Scorer</h3></p>
<p>Hello,</p>
<p>We're here to help you score a "Hand and Foot" or "Hand, Knee, and Foot" game.<br/>  If you're using a phone, this app works best if you turn your phone sideways.</p>
<p></p>
<p>First, we need to know which game you're playing:</p>

<form onsubmit="return IntroNoSubmit(event)">
    <div>
        <input type="radio" class="btn-check" name="game" value="HF" id="introGameHandFoot" autocomplete="off" onchange="introBtnGameClicked();">
        <label class="btn btn-outline-success mb-3 me-3" for="introGameHandFoot">Hand and Foot</label>

        <input type="radio" class="btn-check" name="game" value="HKF" id="introGameHandKneeFoot" autocomplete="off" onchange="introBtnGameClicked();">
        <label class="btn btn-outline-success mb-3 me-3" for="introGameHandKneeFoot">Hand, Knee, and Foot</label>
    </div>
    <div id="introNumberOfTeamsDiv" class="d-none border-top">
        <p>OK, now, how many teams do you have?</p>
        <p>(Even a single player could be a Team)</p>
        <div>
            <input type="radio" class="btn-check" name="numTeams" value="2" id="introNumTeams2" autocomplete="off" onchange="introBtnNumTeamsClicked();">
            <label class="btn btn-outline-success mb-3 me-3" for="introNumTeams2">2</label>

            <input type="radio" class="btn-check" name="numTeams" value="3" id="introNumTeams3" autocomplete="off" onchange="introBtnNumTeamsClicked();">
            <label class="btn btn-outline-success mb-3 me-3" for="introNumTeams3">3</label>
        </div>
    </div>
    <div id="introTeamNamesDiv" class="d-none border-top">
        <p>Great!  Now let's name your teams:</p>
        <div id="introTeamNamesWrapper">
            @for ($i = 1; $i <= $maxNumberOfTeams; $i++)
                <div class='mb-3 row' id="introTeamName{{$i}}Div">
                    <label class='col-sm-2 col-form-label' for='introTeamName{{$i}}'>Team {{$i}} is</label>
                    <div class='col-sm-10'>
                        <input type='text' class='form-control' id='introTeamName{{$i}}'>
                    </div>
                </div>
            @endfor
        </div>
        <div>
            <h5>When entering your scores:</h5>
            <ul>
                <li>
                    If you have a keyboard with arrow keys, you can use the up and down keys to quickly change a value.
                </li>
                <li>
                    If you don't have arrow keys, click or tap the "+" and "-" buttons.
                </li>
                <li>
                    You can also type a value directly into a field.
                </li>
            </ul>
            When you're done scoring a round, click or tap the giant green "Done with Round" button to check that you've got everything entered and to take you to the next round.
        </div>
        <input type="button" class="btn btn-primary mb-3" id="introBtnLetsPlay" value="Let's Play!" onclick="introBtnLetsPlayClicked();">
    </div>
</form>

<script>
    function IntroNoSubmit(event) {
        event.preventDefault();
        return false;
    }

    function ShowHideGameFields(game) {
        games.forEach((gameType) => {
            let fields = document.getElementsByClassName("game"+gameType)
            Array.from(fields).forEach(function (elem) {
                if (gameType == game || game == "")
                    elem.classList.remove("d-none")
            else
                elem.classList.add("d-none")
            });
        })
    }

    function introBtnGameClicked() {
        ShowElementById("introNumberOfTeamsDiv")
        let game = document.querySelector('input[name="game"]:checked').value;
        SetCookie("game", game)
        let fields = document.getElementsByClassName("RoundTeamField")
        ShowHideGameFields(game)

        let scorer = document.getElementById("HandKneeFootScorer")
        let theRules = document.getElementById("TheRules")        
        if (game == HKF) {
            scorer.innerText = "Hand Knee and Foot Scorer"
            theRules.href = "{{env('HKF_RULES_HREF')}}"
        }
        else {
            scorer.innerText = "Hand and Foot Scorer"
            theRules.href = "{{env('HF_RULES_HREF')}}"
        }

        Array.from(fields).forEach((field) => {
            let fieldLabel = field.getAttribute("fieldLabel")
            if (gameParams[game][fieldLabel]) {
                let rowNumber = field.getAttribute("RowNumber")
                let roundNumber = field.getAttribute("RoundNumber")
                let rowElem = document.getElementById("Round"+roundNumber+"Row"+rowNumber)

                if (typeof gameParams[game][fieldLabel]["ignore"] === 'undefined' ||
                    !gameParams[game][fieldLabel]["ignore"]) {
                    rowElem.classList.remove("d-none");
                }
                else {
                    // field.value = ""
                    rowElem.classList.add("d-none");
                }
                if (gameParams[game][fieldLabel]["min"]) {
                    field.setAttribute("min", gameParams[game][fieldLabel]["min"])
                }
                if (gameParams[game][fieldLabel]["max"]) {
                    field.setAttribute("max", gameParams[game][fieldLabel]["max"])
                }
                if (gameParams[game][fieldLabel]["step"]) {
                    field.setAttribute("step", gameParams[game][fieldLabel]["step"])
                }
                if (gameParams[game][fieldLabel]["label"]) {
                    let rowNumber = field.getAttribute("RowNumber")
                    let roundNumber = field.getAttribute("RoundNumber")
                    let label = document.getElementById(rowNumber+"_"+roundNumber+"_Label")
                    label.innerText =  gameParams[game][fieldLabel]["label"]
                }
            }
        })
        UpdateTotals(true)
    }
    
    function introBtnNumTeamsClicked() {
        introShowHideTeamNames()
        let numTeams = document.querySelector('input[name="numTeams"]:checked').value;
        SetCookie("numTeams", numTeams)
    }
    
    function introBtnLetsPlayClicked() {
        for (let i = 1; i <= maxNumberOfTeams; i++) {
            SetCookie("introTeamName"+i, document.getElementById("introTeamName"+i).value)
        }

        for (let i = 1; i <= maxNumberOfRounds; i++) {
            let btnRound = document.getElementById("btnRound"+i)
            btnRound.classList.remove("RoundDisabled")
        }
        let btnRound1 = document.getElementById("btnRound1")
        btnRound1.click()

        // On some browsers, the first field is hidden behind our frozen header line (Round #, Team Names).
        // This should fix that.
        let first = document.getElementsByClassName("Round1Team1Field")[0]
        first.scrollIntoView({ block: "center" })
        first.focus()
    }

    function introSetDefaultTeamNames() {
        let result = ["Us", "Them", "The Other Guys"]

        for (let i = 1; i <= maxNumberOfTeams; i++) {
            let elem = document.getElementById("introTeamName"+i)
            if (elem.value == "")
                elem.value = result[i-1]
        }
    }

    function introShowHideTeamNames() {
        ShowElementById("introTeamNamesDiv")
        let numTeams = GetNumberOfTeams()
        let teamNames = GetTeamNames()

        for (let i = 1; i <= maxNumberOfTeams; i++) {
            let teamNameDivId = "introTeamName"+i+"Div"
            let teamNameDivElem = document.getElementById(teamNameDivId)

            let teamNameId = "introTeamName"+i
            let teamNameElem = document.getElementById(teamNameId)
            teamNameElem.value = teamNames[i-1]

            if (i <= numTeams)
                ShowElement(teamNameDivElem)
            else
                HideElement(teamNameDivElem)
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        introSetDefaultTeamNames()

        let game = GetCookie("game")
        if (game) {
            if (game == "HF") {
                let elem = document.getElementById("introGameHandFoot")
                elem.checked = true
                let event = new Event('change')
                elem.dispatchEvent(event)
            }
            if (game == "HKF") {
                let elem = document.getElementById("introGameHandKneeFoot")
                elem.checked = true
                let event = new Event('change')
                elem.dispatchEvent(event)
            }
        }

        let numTeams = GetCookie("numTeams")
        if (numTeams) {
            let elem = document.getElementById("introNumTeams"+numTeams)
            elem.checked = true
            let event = new Event('change')
            elem.dispatchEvent(event)
        }

        for (let i = 1; i <= maxNumberOfTeams; i++) {
            let teamName = GetCookie("introTeamName"+i)
            if (teamName)
                document.getElementById("introTeamName"+i).value = teamName
        }
    })
</script>
