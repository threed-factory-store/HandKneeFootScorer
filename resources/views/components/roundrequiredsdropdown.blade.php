@php
    $fieldLabel =  (string) $fieldLabel;
    $atLeastOne = isset($atLeastOne) ? $atLeastOne : "";
    $atLeastOneValue = isset($atLeastOneValue) ? $atLeastOneValue : "";
    $atLeastOneMsg = isset($atLeastOneMsg) ? $atLeastOneMsg : "";
    $Help = isset($Help) ? $Help : "";
@endphp

<div class="row mb-3" id="Round{{$roundNumber}}Row{{$rowNumber}}">
    <div class="col" id="{{$rowNumber}}_{{$roundNumber}}_Label">
        {{$fieldLabel}}
    </div>

    @php
        $requireds = [
            "A - All Requireds (11,300)"                => 11300,
            "W - All Requireds Except Wilds (8,800)"    =>  8800,
            "5 - All Requireds Except 5's (8,300)"      =>  8300,
            "7 - All Requireds Except 7's (6,300)"      =>  6300,
            "D - All Requireds Except Dirtys (11,000)"  => 11000,
            "N - None of the above (0)"                 => 0
        ];
    @endphp

    @for ($i = 1; $i <= $maxNumberOfTeams; $i++)
        @php
            $fieldName = $FieldName[$fieldLabel];
            $baseId = $fieldName."_".$i."_".$roundNumber;
        @endphp
        <div class="col" id="{{$rowNumber}}_{{$i}}_{{$roundNumber}}_Col">
            <div class="input-group mb-3">
                <select id="{{$baseId}}" 
                        FieldLabel="{{$fieldLabel}}" 
                        RowNumber="{{$rowNumber}}"
                        RoundNumber="{{$roundNumber}}" 
                        TeamNumber="{{$i}}" 
                        atLeastOne="{{$atLeastOne}}" 
                        atLeastOneValue="{{$atLeastOneValue}}" 
                        atLeastOneMsg="{{$atLeastOneMsg}}"
                        class="form-control RoundTeamField Round{{$roundNumber}}Field Round{{$roundNumber}}Team{{$i}}Field" 
                        onchange="UpdateTotals(true)" >
                    <option selected="selected" value="">Select One</option>
                    @foreach ($requireds as $name => $value)
                        <option value="{{$value}}">
                            {{$name}}
                        </option>
                    @endforeach
                </select>
                @if ($Help) <span class="help-block">{{$Help}}</span> @endif
            </div>
        </div>
    @endfor
</div>
