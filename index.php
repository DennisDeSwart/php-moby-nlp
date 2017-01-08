<?php

    /**
     * Instantiate
     */
    require_once __DIR__.'/bootstrap.php'; 
?>


<!-- HEADER -->
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <title>PHP Moby NLP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
<!-- END HEADER -->


<script> 
    $(document).ready(function() {
        $("#btnHelp").click(function(){
            document.getElementById("thoughtForm").reset();
            $( "#thoughtForm" ).submit(); // submitting an empty form will show the help
        });
    });
</script>    


<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" 
            && array_key_exists("verb", $_POST) && !empty($_POST["verb"]) 
            && array_key_exists("noun", $_POST) && !empty($_POST["noun"])) {

        /**
         * Get the verb and the noun, do some basic validation
         */
        $verb = filter_var($_POST["verb"], FILTER_SANITIZE_STRING);
        $noun = filter_var($_POST["noun"], FILTER_SANITIZE_STRING);

        /**
         * Get the synonym list for the verb and the noun
         */
        $verbList = MobyThesaurus::GetSynonyms($verb);
        $nounList = MobyThesaurus::GetSynonyms($noun);

        /**
         * If it can't find the word, set an error message
         */
        if(array_key_exists('0', $verbList) && $verbList[0] !== $verb){
            // can't find the verb      
            $errorMessage = 'Can not find this verb. Try another verb';
            
        } elseif(array_key_exists('0', $nounList) && $nounList[0] !== $noun){
            // can't find the noun
            $errorMessage = 'Can not find this noun. Try another noun';
            
        } else {
            
            // Get complete thoughts
            foreach($verbList as $verbValue){
                if (in_array($verbValue, $nounList) && $verbValue !== $verb) {
                    $thoughtMatches[] = array('Thoughts found' => $verbValue);
                }
            }

            // get thoughts text
            foreach($verbList as $verbValue){
                foreach($nounList as $nounValue){
                    $regex = '/\b'.$verbValue.'\b/';
                    preg_match($regex, $nounValue, $match, PREG_OFFSET_CAPTURE);
   
                    if($match && $match[0][1] !==0 && $verbValue !== $noun){
                        $fullThoughtText[] = array('Matched text' => $nounValue, 'Based on thought' => $verbValue); 
                    } elseif (strpos($nounValue, $verbValue) && ($verbValue !== $noun)) {            
                        $partThoughtText[] = array('Partial matched text<br />( this matches only part of the thought,<br /> it is not always correct)' => $nounValue, 'Based on thought' => $verbValue); 
                    }         
                }
            }
        }   
    } elseif (empty($_POST["verb"]) && empty($_POST["noun"])){
        // do nothing: help will show
    } elseif (empty($_POST["verb"])){
        $errorMessage = 'Verb field is empty. Please fill in the verb field.';
    } elseif (empty($_POST["noun"])){
        $errorMessage = 'Noun field is empty. Please fill in the noun field.';
    }
?>


    <body>
    <div class="container">
    
<!-- START FORM -->        
    <h2>PHP Moby NLP: complete thoughts form</h2>
    <br>

    <form id="thoughtForm" class="form-horizontal" method="post" action=''>  

        <div class="form-group">
          <label class="col-sm-2 control-label">Enter a verb:</label>
          <div class="col-sm-6">
              <input class="form-control" id="inputVerb" name="verb" type="text" value="">
          </div>
        </div>

        <div class="form-group">
           <label class="col-sm-2 control-label">Enter a noun:</label>
           <div class="col-sm-6">
             <input class="form-control" id="inputNoun" name="noun" type="text" value="">
           </div>
         </div>
        <br />
        
        <input type="submit" value="Submit" class="btn btn-primary btn-lg">
        <button style="margin-left: 20px" id="btnHelp" type="button" class="btn btn-info btn-lg">Show help</button>

        <br /><br />
    </form> 
<!-- END FORM -->        
    
    
<?php
    if(!empty($errorMessage)){
        
        echo '<div class="alert alert-danger"><strong>'.$errorMessage.'</strong></div>';
        
    } elseif ($_SERVER["REQUEST_METHOD"] != "POST" || empty($verb) || empty($noun)) {
?>    

<!-- START HELP -->
    <h3>Examples:</h3>
     <ul class="list-group">
         <li class="list-group-item">Try <b>verb ="work" </b> and <b>noun = "job"</b>. The result will be thoughts that have to do with working on a job, like "achievement", "assignment" and "labor"</li>
        <li class="list-group-item">Try <b>verb ="write"</b> and <b>noun = "document</b>". It will find thoughts like "copy", "draft" and "file" and "print"</li> 
    </ul>
    
    <h3>How does it work?</h3>
     <ul class="list-group">
        <li class="list-group-item">The code looks for "complete thoughts", see the link to Wikipedia here:<br />
            <a href="https://simple.wikipedia.org/wiki/Simple_sentence">We need at least one verb and one noun together for a complete thought</a></li>
        <li class="list-group-item">First enter a verb, like "walk", "talk", "run". It is best to user the base form or lemma. So do not use "running", but use "run". This program can be used to get the lemma for you:
        <a href="https://github.com/DennisDeSwart/php-stanford-corenlp-adapter">PHP-Stanford-CoreNLP-Adapter (at GitHub)</a><br />
        <a href="https://www.phpclasses.org/package/10056-PHP-Natural-language-processing-using-Stanford-server.html">PHP-Stanford-CoreNLP-Adapter (at PHPClasses.org)</a></li>
        <li class="list-group-item">Second, enter a noun like "dog", "cat", "house" or "car".</li>
        <li class="list-group-item">Finally, press Submit, wait for the result. The result may take up to 2 seconds.</li>
    </ul>
     <h3>What can I do with the result?</h3>
     <ul class="list-group">
        <li class="list-group-item">First, you can use it in spam killers: if there are complete thoughts in a text, the text is probably not fake.</li>
        <li class="list-group-item">Second: you can use this for creating reports and understanding text.</li>
        <li class="list-group-item">Third: this could be used as suggestions in search engines.</li>
    </ul>
    <h3>I would like to use a different set of words (=thesaurus). Can I do that?</h3>
     <ul class="list-group">
        <li class="list-group-item">Yes. This script uses the Moby Thesaurus that is based on the English language from 1996. It doesn't have many modern words. You can change the Thesaurus by using an API.
        However, this is not programmed yet. To make it happen, there needs to be an API connection. Also the existing Synonym function needs to be re-written to use this API.</li>
        <li class="list-group-item">I want to write a script to use the Urban Dictionary API. This would make it talk "urban". Here is an example on how to connect:<br />
        <a href="https://github.com/zdict/zdict/wiki/Urban-dictionary-API-documentation">Example of the Urban Dictionary API on Github</a></li>
        <li class="list-group-item">Or you could use any other "Thesaurus" API</li>
    </ul>
    <h3>Please be patient when submitting: the script can take a few seconds</h3>
     <ul class="list-group">
        <li class="list-group-item">The code needs to search the whole Moby Thesaurus, this can take a few seconds. 
            This class will probably be a lot faster by using SQL instead of files. However, there is no SQL file of the thesaurus available yet.</li>
    </ul>   
<!-- END HELP -->
    
    
<?php
    } else {
?>

    <table class="table table-bordered">
    <th colspan="3"><h3> 
        Verb = "<?php echo $verb ?>",
        noun = "<?php echo $noun ?>"</h3></th>
        <tr>
            <td>
                <?php   
                    $template = new Template();
                    if(!empty($thoughtMatches)){
                       $template->getTable($thoughtMatches, '');
                    } else {
                        $template->getTable(array(), '', 'No thought matches found');
                    }
                ?>
            </td>
            <td>
                <?php   
                    $template = new Template();
                    if(!empty($fullThoughtText)){
                       $template->getTable($fullThoughtText, '');
                    } else {
                        $template->getTable(array(), '', 'No thought text was found');
                    }
                ?>
            </td>
            <td>
                <?php   
                    $template = new Template();
                    if(!empty($partThoughtText)){
                       $template->getTable($partThoughtText, '');
                    } else {
                        $template->getTable(array(), '', 'No partial thought text  found');
                    }
                ?>
            </td>
        </tr>
    </table>

<?php
    }
?> 
     
</div>
</body>
</html>