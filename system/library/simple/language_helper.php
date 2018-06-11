<?php
class LanguageHelper {
    private $languages = null;
    private $currentLanguage = '';
    private $data = array();
    private $clearedData = array();
    private $replace = array(
        'ru' => 'russian',
        'en' => 'english'
    );

    public function __construct($languages, $currentLanguage) {
        $this->languages = $languages;
        $this->currentLanguage = $currentLanguage ? $currentLanguage : 'en';

        foreach ($languages as $language) {
            if ($language['directory'] == 'default') {
                $language['directory'] = $this->replace[$currentLanguage];
            }

            $file = DIR_LANGUAGE . $language['directory'] . '/module/simple.php';

            if (file_exists($file)) {
                $_ = array();
                $this->data[$language['code']] = array();

                require($file);

                $this->data[$language['code']] = array_merge($this->data[$language['code']], $_);
            }
        }
    }

    public function __destruct() {
        $export = "<?php\n";
        foreach ($this->clearedData as $id => $text) {
            $export .= "\$_['$id'] = '".addslashes($text)."';\n";
        }
        //file_put_contents('simple.php', $export);
    }

    public function get($id, $onlyText = false) {
        return $onlyText ? $this->getPlainText($id) : '<span class="language-helper" id="'.$id.'">'.$this->getPlainText($id).'</span>';
    }

    public function getPlainText($id) {
        $text = !empty($this->data[$this->currentLanguage][$id]) ? $this->data[$this->currentLanguage][$id] : $id;
        $text_en = !empty($this->data['en'][$id]) ? $this->data['en'][$id] : $id;

        if ($text == $id) {
            $text = $text_en;
        }

        $this->clearedData[$id] = $text;

        return $text;
    }

    public function set($languageCode, $id, $text) {
        $this->data[$languageCode][$id] = $text;
    }

    public function save() {
        foreach ($this->languages as $language) {

            $file = DIR_LANGUAGE . $language['directory'] . '/module/simple.php';

            $rows = array();

            foreach ($this->data[$language['code']] as $id => $text) {
                $rows[] = '$_[\''.$id.'\'] = \''.str_replace('\'', '\\\'', $text).'\';';
            }

            @file_put_contents($file, "<?php\r\n".implode("\r\n", $rows));

        }
    }

}