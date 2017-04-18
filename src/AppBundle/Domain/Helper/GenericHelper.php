<?php
/**
 * Created by PhpStorm.
 * User: AlperSalviz
 * Date: 16.4.2017
 * Time: 21:47
 */

namespace AppBundle\Domain\Helper;

use AppBundle\Data\Repository\LabelRepository; 
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class GenericHelper extends \Twig_Extension
{

    private $_container;
    private $_labelRepository;
    private $_currentLanguage;
    private $_requestStack;

    public function __construct(ContainerInterface $container, RequestStack $requestStack, LabelRepository $labelRepository)
    {
        $this->_labelRepository = $labelRepository;
        $this->_container = $container;
        $this->_requestStack = $requestStack;
    }
    public function getFunctions()
    {
        return array(
            'Label' => new \Twig_Function_Method($this, 'Label')
        );
    }

    public function Label($text)
    {
        $code = $this->_container->get('session')->get('language');
        $label = $this->_labelRepository->getLabel($code,$text); 
        if ($label == null || $label == false){
            $label = $this->_labelRepository->LabelAllAdd($text);
            if ($label == true){
                return "lbl_".$text;
            }else{
                return null;
            }

        }else if( $label["label"] == ""){
            return "lbl_".$text;
        }else{
           return $label["label"];
        }

    }
}