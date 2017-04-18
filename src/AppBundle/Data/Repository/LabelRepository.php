<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 16.4.2017
 * Time: 21:56
 */

namespace AppBundle\Data\Repository;


use AppBundle\Data\Model\Label;
use AppBundle\Data\Model\Language;
use AppBundle\Domain\Model\PagedList;
use Symfony\Component\Config\Definition\Exception\Exception;

class LabelRepository extends BaseRepository
{
    public function getLabel($language, $labelCode)
    {
        try {
            $languageQuery = "SELECT * FROM language 
                            WHERE code = :code";

            $languageResult = $this->getConnection()->prepare($languageQuery);
            $languageResult->execute(array(
                ":code" => $language
            ));

            $languageResult = $languageResult->fetch();

            if ($languageResult == false)
                return false;
            $code = $languageResult["ID"];
            $query = "SELECT * FROM label
                    WHERE label_code = :label_code AND language_id = :language_id";

            $result = $this->getConnection()->prepare($query);

            $result->execute(array(
                ":label_code" => $labelCode,
                ":language_id" => $code
            ));

            $result = $result->fetch();

            if ($result === false)
                return false;

            return $result;

        } catch (Exception $e) {

        }

    }

    public function getLanguage()
    {
        try {
            $languageQuery = "SELECT * FROM language";

            $languageResult = $this->getConnection()->prepare($languageQuery);
            $languageResult->execute();

            $languageResults = $languageResult->fetchAll();

            if ($languageResults == false)
                return false;

            $languages = array();

            foreach ($languageResults as $result) {
                $languages[] = (new Language())->MapFrom($result);
            }

            return $languages;

        } catch (Exception $e) {

        }

    }

    public function getLanguageById($lang)
    {
        try {
            $languageQuery = "SELECT * FROM language WHERE ID = :id";

            $languageResult = $this->getConnection()->prepare($languageQuery);
            $languageResult->execute(array(
                ':id' => $lang
            ));

            $languageResult = $languageResult->fetch();

            if ($languageResult == false)
                return false;


                $language = (new Language())->MapFrom($languageResult);


            return $language;

        } catch (Exception $e) {

        }

    }

    public function getLabelByLanguage($id,$offset = 0, $limit = 5, $searchKey = null)
    {
        try {
            $countQuery='SELECT COUNT(*) as row_count 
                FROM label as l WHERE ';

            if($searchKey !== null)
            {
                $countQuery .='l.label_code
                        LIKE "%'.$searchKey.'%" AND ';
            }
            $countQuery .= "l.language_id = :language_id ";
            $countResult = $this->getConnection()->prepare($countQuery);
            $countResult->execute(array(
                ":language_id" => $id
            ));

            $countResult = $countResult->fetch(); 

            if($countResult === null || (int)$countResult['row_count'] == 0)
                return new PagedList(null,0,$limit,$searchKey);



            $query = "SELECT * FROM label as l WHERE ";
            if($searchKey !== null){
                $query .='l.label_code
                        LIKE "%'.$searchKey.'%" AND ';
            }
            $query .='l.language_id = :language_id LIMIT '.$offset.','.$limit;

            $result = $this->getConnection()->prepare($query);

            $result->execute(array(
                ":language_id" => $id
            ));

            $results = $result->fetchAll();
            if ($results === false)
                return new PagedList(null, 0,$limit,$searchKey);

            $labels = array();
            foreach ($results as $result) {
                $labels[] = (new Label())->MapFrom($result);
            }
            $list = new PagedList($labels,(int)$countResult['row_count'],$limit,$searchKey);

            return $list;

        } catch (Exception $e) {
            return false;
        }

    }

    public function getLabelById($id)
    {

        try {
            $query = "SELECT * FROM label
                    WHERE ID = :id";

            $result = $this->getConnection()->prepare($query);

            $result->execute(array(
                ":id" => $id
            ));

            $result = $result->fetch();

            if ($result === false)
                return false;

            $label = (new Label())->MapFrom($result);

            return $label;

        } catch (Exception $e) {
            return false;
        }

    }

    public function LabelUpdate($id, $label, $labelCode)
    {
        try {
            $query = "UPDATE label SET
                  label_code = :label_code ,label = :label 
                  WHERE ID = :id";

            $result = $this->getConnection()->prepare($query);

            $result->execute(array(
                ":id" => $id,
                ":label_code" => $labelCode,
                ":label" => $label
            ));


            if ($result === false)
                return false;


            return true;

        } catch (Exception $e) {
            return false;
        }
    }

    public function LabelAdd($language_id, $label, $labelCode)
    {
        try {
            $query = "INSERT INTO label
                      (label_code, label, language_id) VALUES 
                      (:label_code,:label , :language_id)";
            $result = $this->getConnection()->prepare($query);

            $result->execute(array(
                ":language_id" => $language_id,
                ":label_code" => $labelCode,
                ":label" => $label
            ));
            if ($result === false)
                return false;


            return true;

        } catch (Exception $e) {
            return false;
        }

    }

    public function LabelAllAdd($labelCode)
    {
        try {
            $languages = $this->getLanguage();

            foreach ($languages as $language){


            $query = "INSERT INTO label
                      (label_code, label, language_id) VALUES 
                      (:label_code,:label , :language_id)";
            $result = $this->getConnection()->prepare($query);

            $result->execute(array(
                ":language_id" => $language->ID,
                ":label_code" => $labelCode,
                ":label" => ""
            ));

            if ($result === false)
                return false;

            }
            return true;
        } catch (Exception $e) {
            return false;
        }

    }
}