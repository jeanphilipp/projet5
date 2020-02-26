<?php
namespace App\Controller\Back;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class CommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="app_comments_show")
     */
    public function showAll(){
        $entityManager = $this->getDoctrine()->getManager();
        $comments = $entityManager->getRepository(Comment::class)->findAll();
        return $this->render('back/comment/show_all.html.twig',[
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/admin/comment/delete/{id}",name="app_comment_delete")
     */
    public function delete(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comment::class)->find($id);
        if ($comment instanceof Comment) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_comments_show');
    }
}