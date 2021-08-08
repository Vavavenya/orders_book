<?php
namespace App\Controller;

use App\Entity\Book;
use App\Form\Type\BookType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookController
 * @package App\Controller
 * @Route("/api", name="book_api")
 */
class BookController extends AbstractApiController
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/books", name="books_list", methods={"GET"})
     */
	public function getListOfBooks(Request $request): Response
	{
		$books = $this->getDoctrine()->getRepository(Book::Class)->findAll();

        return $this->respond($books);
	}

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     *
     * @throws NotFoundHttpException
     *
     * @Route("/books/{id}", name="book_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */
	public function getBooksById(Request $request, int $id): Response
	{
        $book = $this->getBookById($id);

        return $this->respond($book);
	}

    /**
     * @param Request $request
     * @return Response
     * @Route("/books", name="books_create", methods={"POST"})
     */
	public function createBook(Request $request): Response
	{
		$form = $this->buildForm(BookType::class);
		$form->handleRequest($request);

		if (!$form->isSubmitted() || !$form->isValid()){
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
		}

		$book = $form->getData();

		$this->getDoctrine()->getManager()->persist($book);
		$this->getDoctrine()->getManager()->flush();

        return $this->respond($book);
	}

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     *
     * @throws NotFoundHttpException
     *
     * @Route("/books/{id}", name="books_edit", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function editBook(Request $request, int $id): Response
    {
        $book = $this->getBookById($id);

        $form = $this->buildForm(BookType::class, $book, [
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $book = $form->getData();

        $this->getDoctrine()->getManager()->persist($book);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($book);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     *
     * @throws NotFoundHttpException
     *
     * @Route("/books/{id}", name="books_remove", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function removeBooks(Request $request, int $id): Response
    {
        $book = $this->getBookById($id);

        $this->getDoctrine()->getManager()->remove($book);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($book);
    }

    /**
     * @param int $id
     * @return mixed|object
     *
     * @throws NotFoundHttpException
     */
    protected function getBookById(int $id): Book
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }

        return $book;
    }

}