<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    public function fieldLabelToBaseFieldName($fieldLabel) {
        $result = "edt".str_replace(" ", "", $fieldLabel);
        $result = str_replace("'", "", $result);
        return $result;
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->share("maxNumberOfTeams", 3); 
        view()->share("maxNumberOfRounds", 4);

        # Be careful with the values in $flList...
        # We use them as keys in the $fieldNames array below, so they
        # can't contain single quotes for example.
        #
        # The idea with flList is that the left side never needs to change and 
        # the right side can be changed at will and the app will still work.
        $flList = [
            "flRequiredBooks"           => "Required Books",
            "flBonusForGoingOut"        => "Bonus For Going Out",
            "flRedThrees"               => "Red Threes",
            "flAdditionalRedBooks"      => "Additional Red Books",
            "flAdditionalDirtyBooks"    => "Additional Dirty Books",
            "flAdditionalWildBooks"     => "Additional Wild Books",
            "flAdditionalBook5s"        => "Additional Book 5s",
            "flAdditionalBook7s"        => "Additional Book 7s",
            "flCardCount"               => "Card Count of all played cards",
            "flMinusCount"              => "Minus Count of all unplayed cards"
        ];

        view()->share("flList", $flList);
        foreach($flList as $key => $value) {
            view()->share($key, $value);
        }

        $fieldNames = [];
        foreach($flList as $key => $value) {
            $fieldNames[$value] = $this->fieldLabelToBaseFieldName($value);
        }

        view()->share("FieldName", $fieldNames );

        view()->share("HF", "HF");
        view()->share("HKF", "HKF");
    }
}
