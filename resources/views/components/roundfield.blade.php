@php
    $exactlyOne = isset($exactlyOne) ? $exactlyOne : "";
    $exactlyOneMsg = isset($exactlyOneMsg) ? $exactlyOneMsg : "";
    $values = isset($values) ? $values : "";
    $Help = isset($Help) ? $Help : "";
    $fieldLabel =  (string) $fieldLabel;
@endphp

<div class="row mb-3" id="Round{{$roundNumber}}Row{{$rowNumber}}">
    <div class="col" id="{{$rowNumber}}_{{$roundNumber}}_Label">
        {{$fieldLabel}}
    </div>

    @for ($i = 1; $i <= $maxNumberOfTeams; $i++)
        @php
            $fieldName = $FieldName[$fieldLabel];
            $baseId = $fieldName."_".$i."_".$roundNumber;
        @endphp
        <div class="col" id="{{$rowNumber}}_{{$i}}_{{$roundNumber}}_Col">
            <div class="input-group mb-3">
                <button class="btn btn-outline-secondary" type="button" id="{{$baseId}}_Minus" tabindex="-1">-</button>
                <input  id="{{$baseId}}" 
                        FieldLabel="{{$fieldLabel}}" 
                        RowNumber="{{$rowNumber}}"
                        RoundNumber="{{$roundNumber}}" 
                        TeamNumber="{{$i}}" 
                        type="text" 
                        exactlyOne="{{$exactlyOne}}" 
                        exactlyOneMsg="{{$exactlyOneMsg}}"
                        class="form-control RoundTeamField Round{{$roundNumber}}Field Round{{$roundNumber}}Team{{$i}}Field" 
                        min="{{$min}}" max="{{$max}}" step="{{$step}}"
                        values="{{$values}}"
                        onchange="OnRoundFieldChange(event)" exactlyOne="{{$exactlyOne}}" 
                        onkeydown="return OnRoundFieldKeyDown(event)" >
                <button class="btn btn-outline-secondary" type="button" id="{{$baseId}}_Plus" tabindex="-1">+</button>
                @if ($Help) <span class="help-block">{{$Help}}</span> @endif
            </div>
        </div>
    @endfor
</div>
<script>
    function OnRoundFieldChange(evt) {
        let target = evt.target 
        let value = target.value
        var min = target.getAttribute("min");
        var max = target.getAttribute("max");
        var step = target.getAttribute("step");

        value = cleanNumber(value, min, max, step)
        if (value != target.value) {
            let fieldLabel = target.getAttribute("FieldLabel")

            alert(fieldLabel+" only accepts multiples of "+step)
            target.focus()
        }

        UpdateTotals(true)
    }

    function OnRoundFieldKeyDown(evt) {
        if (/^(ArrowUp)$/.test("" + evt.key)) {
            evt.preventDefault();
            btnPlus =document.getElementById(evt.target.id+"_Plus")
            btnPlus.click()
            return true
        }
        if (/^(ArrowDown)$/.test("" + evt.key)) {
            evt.preventDefault();
            btnMinus =document.getElementById(evt.target.id+"_Minus")
            btnMinus.click()
            return true
        }
        if (/^(\-|0|1|2|3|4|5|6|7|8|9|Backspace|Delete|ArrowLeft|ArrowRight|Tab)$/.test("" + evt.key)) {
            return true
        }
        if (/^(a|z|x|c|v)$/.test("" + evt.key)) {
            if (evt.getModifierState("Control"))
                return true
        }
        
        evt.preventDefault();
        return false
    }
</script>
