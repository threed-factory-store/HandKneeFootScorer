<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Hand Knee Foot</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="css/tailwind.css" />
        <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/common.css" />
        <link rel="stylesheet" href="css/tabs.css" />
        <link rel="stylesheet" href="css/app.css" />

        <script type="text/javascript" src="js/app.js"></script>
        <script type="text/javascript" src="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    </head>

    <script type="text/javascript" src="js/common.js"></script>
    <script>
        // gameParams maps game parameters to specific games and field labels.
        // For example, the game Hand & Foot has one set of parameters for the Bonus For Going Out, 
        // and Hand, Knee, and Foot has a different set.
        var flList =  @json($flList);
        const HF = "HF"
        const HKF = "HKF"
        const NONE = "NONE"
        if (HF  != {{$HF}})  throw new Error('HF != $HF');
        if (HKF  != {{$HKF}})  throw new Error('HKF != $HKF');
        var games = [HF, HKF, NONE]
        var gameParams = {}
        gameParams[HF] = {
            "{{$flBonusForGoingOut}}": {
                step: 100
            },
            "{{$flRedThrees}}": {
                ignore: true
            },
            "{{$flAdditionalRedBooks}}": {
                label: "Red (Clean) Books"
            },
            "{{$flAdditionalDirtyBooks}}": {
                label: "Black (Dirty) Books"
            },
            "{{$flAdditionalWildBooks}}": {
                ignore: true
            },
            "{{$flAdditionalBook5s}}": {
                ignore: true
            },
            "{{$flAdditionalBook7s}}": {
                ignore: true
            },
        }
        // round.blade.php has the HKF values.
        gameParams[HKF] = {
            "{{$flBonusForGoingOut}}": {
                step: 200
            },
            "{{$flRedThrees}}": {
                ignore: false
            },
            "{{$flAdditionalRedBooks}}": {
                label: "Additional Red (Clean) Books"
            },
            "{{$flAdditionalDirtyBooks}}": {
                label: "Additional Black (Dirty) Books"
            },
            "{{$flAdditionalWildBooks}}": {
                ignore: false
            },
            "{{$flAdditionalBook5s}}": {
                ignore: false
            },
            "{{$flAdditionalBook7s}}": {
                ignore: false
            },
        }

        if (maxNumberOfTeams  != {{$maxNumberOfTeams}})  throw new Error('maxNumberOfTeams != $maxNumberOfTeams');
        if (maxNumberOfRounds != {{$maxNumberOfRounds}}) throw new Error('maxNumberOfRounds != $maxNumberOfRounds');
    </script>
    
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <script type="text/javascript" src="js/tabs.js"></script>
        <script type="text/javascript" src="js/round.js"></script>

        <!-- Our Tabs -->
        <div class="tab sticky-top">
            <button id="btnIntro" class="tablinks" onclick="openTab(0)">Intro</button>
            @for ($i = 1; $i <= $maxNumberOfRounds; $i++)
                <button id="btnRound{{$i}}" RoundNumber="{{$i}}" class="tablinks" onclick="return RoundClicked(event, {{$i}})">Round {{$i}}</button>
            @endfor
            <button id="btnNewGame" class="btn btn-outline-success float-end" onclick="NewGame()">New Game</button>
            <button id="btnRules"   class="tablinks btn float-end" onclick="openTab({{$maxNumberOfRounds+1}})">Rules</button>
        </div>

        <!-- The Tab Contents -->
        <div id="tabIntro" class="tabcontent">
            <x-intro/>
        </div>

        @for ($i = 1; $i <= $maxNumberOfRounds; $i++)
            <x-round :roundNumber="$i" />
        @endfor

        <div id="tabRules" class="tabcontent">
            <x-rules/>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let tabNumber = GetCookie("TabNumber", 0)
                openTab(tabNumber)

                let fields = document.getElementsByClassName("RoundTeamField")
                Array.from(fields).forEach((elem) => {
                    AddRoundListeners(elem)
                });
            });
        </script>

        <div class="modal" tabindex="-1" id="resultsModal">
            <div class="modal-dialog">
                <div class="modal-content bg-light">
                    <div class="modal-header">
                        <h5 class="modal-title">The Results</h5>
                        <button id="resultsModalX" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="resultsModalBody">
                    </div>
                    <div class="modal-footer">
                        <button id="resultsModalClose" type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function RoundClicked(event, roundNumber) {
                let elem = event.target
                if (elem.classList.contains("RoundDisabled")) {
                    event.preventDefault()
                    alert("Please fill out the Intro tab and click 'Let\'s Play'")
                    openTab(0)
                    return false
                }
                openTab(roundNumber)
                return true
            }
            modalElem = document.getElementById("resultsModal")
            function CloseResultsModal() {
                modalElem.style.display = "none";
            }

            document.addEventListener('DOMContentLoaded', function () {
                let game = GetCookie("game")
                if (game == "" ||
                    GetNumberOfTeams() == 0) {
                        for (let roundNumber = 1; roundNumber <= maxNumberOfRounds; roundNumber++) {
                            let btnRound = document.getElementById("btnRound"+roundNumber)
                            btnRound.classList.add("RoundDisabled")
                        }
                }
                else {
                    for (let roundNumber = 1; roundNumber <= maxNumberOfRounds; roundNumber++) {
                            let btnRound = document.getElementById("btnRound"+roundNumber)
                            btnRound.classList.remove("RoundDisabled")
                        }
                }

                document.getElementById('resultsModalX').onclick = function() {
                    CloseResultsModal()
                }
                document.getElementById('resultsModalClose').onclick = function() {
                    CloseResultsModal()
                }
                window.onclick = function(event) {
                    if (event.target == modalElem) {
                        modalElem.style.display = "none";
                    }
                }

                let currentRoundNum = CurrentRoundNumber()
                for (let roundNum = 1; roundNum <= maxNumberOfRounds; roundNum++) {
                    UpdateRoundTotals(roundNum, true, roundNum == currentRoundNum)
                }
            });
        </script>
    </body>
</html>
