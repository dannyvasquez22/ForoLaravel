<?php

class CreatePostsTest extends FeatureTestCase
{

	public function test_a_user_create_a_post()
	{
		// Having - una pregunta
		$title = 'Esta es una pregunta';
		$content = 'Este es el contenido';

		$this->actingAs($user = $this->defaultUser());

		//Where - eventos de la prueba
		$this->visit(route('posts.create'))
			->type($title, 'title')
			->type($content, 'content')
			->press('Publicar');

		// Then - resultado
		$this->seeInDatabase('posts', [
			'title' => $title,
			'content' => $content,
			'pending' => true,
			'user_id' => $user->id,
		]);

		// Test a user is redirected to the posts details after creationg it.
		$this->see($title);
	}

	public function test_creating_a_post_requires_authentication()
	{
		$this->visit(route('posts.create'))
			->seePageIs(route('login'));
	}


	function test_create_post_form_validation()
	{
		$this->actingAs($this->defaultUser())
			->visit(route('posts.create'))
			->press('Publicar')
			->seePageIs(route('posts.create'))
			->seeErrors([
				'title' => 'El campo título es obligatorio',
				'content' => 'El campo contenido es obligatorio'
			]);

			//->seeInElement('#field_title.has-error .help-block', 'El campo título es obligatorio')
			//->seeInElement('#field_content.has-error .help-block', 'El campo contenido es obligatorio'); 
	}
}