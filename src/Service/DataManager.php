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

    public function __construct(AuthorRepository $authorRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->authorRepository = $authorRepository;
        $this->serializer = $serializer;
        $this->em = $entityManager;
    }

    public function createFile($filename,$letter=null)
    {
        $data = $this->serializer->serialize($this->getData($letter), 'csv',array(CsvEncoder::DELIMITER_KEY=>' '));
        $data =  preg_replace('/^.+\n/', '', $data);//removing first line where headers are.
        $file = fopen($filename.'.txt','w');
        fwrite($file,$data);
        fclose($file);
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
}