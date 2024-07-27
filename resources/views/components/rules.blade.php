<p><h3 id="HandKneeFootScorer">Hand (Knee) and Foot Scorer</h3></p>
<p><h4><a id="TheRules" target="_blank" href="" onclick="return TheRulesClicked(event)">The Rules</a></h4></p>

<div class="row">
    <div class="col-3">
        <p>
            Scoring your Card Count <br /> (cards played):
        </p>
        <table>
            <tr>
                <td class="border p-2">Joker</td>
                <td class="border p-2 text-end">50</td>
            </tr>
            <tr>
                <td class="border p-2">Ace's and 2's</td>
                <td class="border p-2 text-end">20</td>
            </tr>
            <tr>
                <td class="border p-2">4's through 7's</td>
                <td class="border p-2 text-end">5</td>
            </tr>
            <tr>
                <td class="border p-2">8's through Kings</td>
                <td class="border p-2 text-end">10</td>
            </tr>
        </table>
    </div>
    <div class="col-3">
        <p>
            Scoring your Minus Count <br /> (unplayed cards):
        </p>
        <table>
            <tr class="gameNONE">
                <td class="border p-2"></td>
                <td class="border p-2">Hand & Foot</td>
                <td class="border p-2">Hand Knee & Foot</td>
            </tr>
            <tr>
                <td class="border p-2">Joker</td>
                <td class="border p-2 text-end">-50</td>
                <td class="border p-2 text-end gameNONE">-50</td>
            </tr>
            <tr>
                <td class="border p-2">Ace's and 2's</td>
                <td class="border p-2 text-end">-20</td>
                <td class="border p-2 text-end gameNONE">-20</td>
            </tr>
            <tr>
                <td class="border p-2">Red 3's</td>
                <td class="border p-2 text-end gameHF">-300</td>
                <td class="border p-2 text-end gameHKF">-500</td>
            </tr>
            <tr>
                <td class="border p-2">Black 3's</td>
                <td class="border p-2 text-end gameHF">-5</td>
                <td class="border p-2 text-end gameHKF">-300</td>
            </tr>
            <tr>
                <td class="border p-2">4's through 7's</td>
                <td class="border p-2 text-end">-5</td>
                <td class="border p-2 text-end gameNONE">-5</td>
            </tr>
            <tr>
                <td class="border p-2">8's through Kings</td>
                <td class="border p-2 text-end">-10</td>
                <td class="border p-2 text-end gameNONE">-10</td>
            </tr>
        </table>
    </div>
    <div class="col-4">
        <p>
            Required Meld:
        </p>
        <table>
            <tr class="gameNONE">
                <td class="border p-2">Hand & Foot</td>
                <td class="border p-2">Hand Knee & Foot</td>
                <td class="border p-2"></td>
            </tr>
            <tr>
                <td class="border p-2 gameHF">1st hand</td>
                <td class="border p-2 gameHKF">0-14,995</td>
                <td class="border p-2 text-end">50</td>
            </tr>
            <tr>
                <td class="border p-2 gameHF">2nd hand</td>
                <td class="border p-2 gameHKF">15,000-29,995</td>
                <td class="border p-2 text-end">90</td>
            </tr>
            <tr>
                <td class="border p-2 gameHF">3rd hand</td>
                <td class="border p-2 gameHKF">30,000-49,995</td>
                <td class="border p-2 text-end">120</td>
            </tr>
            <tr>
                <td class="border p-2 gameHF">4th hand</td>
                <td class="border p-2 gameHKF">50,000-up</td>
                <td class="border p-2 text-end">150</td>
            </tr>
        </table>
    </div>
    <div class="col-2">
        <p>
            Number of Decks:
        </p>
        <table>
            <tr>
                <td class="border p-2">2 Teams</td>
                <td class="border p-2">6 Decks</td>
            </tr>
            <tr>
                <td class="border p-2">3 Teams</td>
                <td class="border p-2">7 Decks</td>
            </tr>
        </table>
    </div>
</div>
<script>
    function TheRulesClicked(event) {
        result = event.target.href != "http://localhost/" && event.target.href != "http://handkneefoot.com/" 
        if (!result)
            alert("Please click 'Intro' at the top left, then select a game.")
        return result
    }
</script>