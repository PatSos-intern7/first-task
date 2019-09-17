<?php
namespace App\Service;

use App\Entity;
use App\Repository\AuthorRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class DataManager
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var AuthorRepository
     */
    private $authorRepository;
    /**
     * @var string
     */
    private $finalFileName;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->em = $entityManager;
    }

    public function createFile($filename,$letter=null): void
    {
        $filenameManager = new FileNameManager();
        $finalFileName = $filenameManager->getFileName($filename);
        $data = $this->serializer->serialize($this->getData($letter), 'csv',array(CsvEncoder::DELIMITER_KEY=>' '));
        $data =  preg_replace('/^.+\n/', '', $data);//removing first line where headers are.
        $file = fopen($finalFileName.'.txt', 'wb');
        fwrite($file,$data);
        fclose($file);
        $this->setFinalFileName($finalFileName);
    }

    public function getData($letter = null)
    {
        $query = "
                SELECT author.id,surname , name, COUNT(*) AS books_number 
                FROM author
                JOIN book b ON author.id = b.author_id
                WHERE author.surname LIKE '".$letter."%'
                GROUP BY b.author_id";

        $stmt = $this->em->getConnection()->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function letterCheck($letter): bool
    {
        return isset($letter) && !preg_match('/^[a-zA-Z]$/',$letter);
    }

    private function setFinalFileName(string $finalFileName)
    {
        $this->finalFileName = $finalFileName;
    }

    public function getFinalFileName():string
    {
       return $this->finalFileName;
    }

}