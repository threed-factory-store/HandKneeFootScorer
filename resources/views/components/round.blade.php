<div id="tabRound{{$roundNumber}}" class="tabcontent">
    <div class="row mb-3 sticky-top2 bg-white">
        <div class="col">
            <h3>Round {{ $roundNumber }}</h3>
        </div>

        @for ($i = 1; $i <= $maxNumberOfTeams; $i++)
            <div id="Round{{$roundNumber}}Team{{$i}}Div" class="col">
                <h3 id="Round{{$roundNumber}}Team{{$i}}"></h3>
            </div>
        @endfor
    </div>
    <form onsubmit="return RoundNoSubmit(event)">
        @php $rowNumber = 1; @endphp
        <x-roundrequiredsdropdown :rowNumber="$rowNumber" :roundNumber="$roundNumber" fieldLabel="{{$flRequiredBooks}}" atLeastOne="RequiredBooks" atLeastOneValue="11300" atLeastOneMsg="At least one team should have made all their {{$flRequiredBooks}}."/>
        @php $rowNumber += 1; @endphp
        <x-roundfield :rowNumber="$rowNumber" :roundNumber="$roundNumber" fieldLabel="{{$flBonusForGoingOut}}"      min="0" max="200"    step="200" exactlyOne="BonusForGoingOut" exactlyOneMsg="One team should have a {{$flBonusForGoingOut}}." />
        @php $rowNumber += 1; @endphp
        <x-roundfield :rowNumber="$rowNumber" :roundNumber="$roundNumber" fieldLabel="{{$flRedThrees}}"             min="0" max="1700"    step="100" values="0,100,200,300,400,500,600,1000,1100,1200,1300,1400,1500,1600,1700"/>
        @php $rowNumber += 1; @endphp
        <x-roundfield :rowNumber="$rowNumber" :roundNumber="$roundNumber" fieldLabel="{{$flAdditionalRedBooks}}"    min="0" max="20000"   step="500"/>
        @php $rowNumber += 1; @endphp
        <x-roundfield :rowNumber="$rowNumber" :roundNumber="$roundNumber" fieldLabel="{{$flAdditionalDirtyBooks}}"  min="0" max="60000"   step="300"/>
        @php $rowNumber += 1; @endphp
        <x-roundfield :rowNumber="$rowNumber" :roundNumber="$roundNumber" fieldLabel="{{$flAdditionalWildBooks}}"   min="0" max="20000"  step="2500"/>
        @php $rowNumber += 1; @endphp
        <x-roundfield :rowNumber="$rowNumber" :roundNumber="$roundNumber" fieldLabel="{{$flAdditionalBook5s}}"      min="0" max="12000"   step="3000"/>
        @php $rowNumber += 1; @endphp
        <x-roundfield :rowNumber="$rowNumber" :roundNumber="$roundNumber" fieldLabel="{{$flAdditionalBook7s}}"      min="0" max="20000"  step="5000"/>
        @php $rowNumber += 1; @endphp
        <x-roundfield :rowNumber="$rowNumber" :roundNumber="$roundNumber" fieldLabel="{{$flCardCount}}"             min="0" max="100000" step="5"/>
        @php $rowNumber += 1; @endphp
        <x-roundfield :rowNumber="$rowNumber" :roundNumber="$roundNumber" fieldLabel="{{$flMinusCount}}"            min="-100000" max="0" step="5"/>
    </form>
    <div class="row mb-3">
        <div class="col">
            Total Round {{$roundNumber}}
        </div>
        @for ($i = 1; $i <= $maxNumberOfTeams; $i++)
            <div class="col" id="Total_{{$i}}_{{$roundNumber}}_Col">
                <div class="mb-3">
                    <div id="Total_{{$i}}_{{$roundNumber}}" class="text-center"></div>
                </div>
            </div>
        @endfor
    </div>
    @for ($prevRoundNum=$roundNumber-1; $prevRoundNum >= 1; $prevRoundNum--)
        <div class="row mb-3">
            <div class="col">
                Total Round {{$prevRoundNum}}
            </div>
            @for ($i = 1; $i <= $maxNumberOfTeams; $i++)
                <div class="col" id="Total_{{$i}}_{{$roundNumber}}_{{$prevRoundNum}}_Col">
                    <div class="mb-3">
                        <div id="Total_{{$i}}_{{$roundNumber}}_{{$prevRoundNum}}" class="text-center"></div>
                    </div>
                </div>
            @endfor
        </div>
    @endfor
    @if ($roundNumber > 1)
        <div class="row mb-3">
            @if ($roundNumber < $maxNumberOfRounds)
                <div class="col">
                    Grand Total so far
                </div>
                @for ($i = 1; $i <= $maxNumberOfTeams; $i++)
                    <div class="col" id="GrandTotal_{{$i}}_{{$roundNumber}}_Col">
                        <div class="mb-3">
                            <div id="GrandTotal_{{$i}}_{{$roundNumber}}" class="text-center"></div>
                        </div>
                    </div>
                @endfor
            @else
                <div class="text-center">
                    <h3>Click or tap "Done with Round {{$maxNumberOfRounds}}" to see the totals</h3>
                </div>
            @endif
        </div>
    @endif
    <div>
        <form id="DoneForm" onsubmit="return RoundNoSubmit(event)">
            <div class="row mb-3">
                <button id="btnDone_{{$roundNumber}}" class="btn btn-success float-end" onclick="DoneClicked(event)">Done with Round {{$roundNumber}}</button>
            </div>
        </form>
    </div>

</div>
<script>
    function RoundNoSubmit(event) {
        event.preventDefault();
        return false;
    }

    function DoneClicked(event) {
        event.preventDefault();
        let round = CurrentRoundNumber()
        if (round > 0)
            UpdateRoundTotals(round, false, true)
        return false
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('DoneForm').onsubmit = function(event) {
            event.preventDefault();
            return false
        }
        let round = CurrentRoundNumber()
        if (round > 0)
            UpdateRoundTotals(round, true, false)
    });
</script>
