package com.example.board20.service;

import com.example.board20.domain.entity.Board;
import com.example.board20.domain.repository.BoardRepository;
import com.example.board20.dto.BoardDto;
import jakarta.transaction.Transactional;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageImpl;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;
import org.springframework.web.multipart.MultipartFile;

import java.util.ArrayList;
import java.util.List;


@Service
public class BoardService {
    private BoardRepository boardRepository;


    public BoardService(BoardRepository boardRepository) {
        this.boardRepository = boardRepository;
    }

    @Transactional
    public Long savePost(BoardDto boardDto, MultipartFile uploadFile) {
        return boardRepository.save(boardDto.toEntity()).getId();
    }


    @Transactional
    public Page<BoardDto> getBoardList(Pageable pageable) {
        Page<Board> boardList = boardRepository.findAll(pageable);
        List<BoardDto> boardDtoList = new ArrayList<>();

        for (Board board : boardList) {
            BoardDto boardDto = BoardDto.builder()
                    .id(board.getId())
                    .author(board.getAuthor())
                    .title(board.getTitle())
                    .content(board.getContent())
                    .createdDate(board.getCreatedDate())


                    .build();
            boardDtoList.add(boardDto);
        }
        return new PageImpl<>(boardDtoList, pageable, boardList.getTotalElements());
    }

    @Transactional
    public BoardDto getPost(Long id) {
        Board board = boardRepository.findById(id).get();

        BoardDto boardDto = BoardDto.builder()
                .id(board.getId())
                .author(board.getAuthor())
                .title(board.getTitle())
                .content(board.getContent())
                .createdDate(board.getCreatedDate())
                .originalFileName((board.getOriginalFileName()))
                .savedFilePath((board.getSavedFilePath()))

                .build();
        return boardDto;
    }

    @Transactional
    public void deletePost(Long id) {
        boardRepository.deleteById(id);
    }


    @Transactional
    public Page<BoardDto> search(String keyword, Pageable pageable) {
        List<Board> boardList = boardRepository.findByTitleContaining(keyword, pageable);
        List<BoardDto> boardDtoList = new ArrayList<>();

        for (Board board : boardList) {
            BoardDto boardDto = BoardDto.builder()
                    .id(board.getId())
                    .author(board.getAuthor())
                    .title(board.getTitle())
                    .content(board.getContent())
                    .createdDate(board.getCreatedDate())
                    .originalFileName(board.getOriginalFileName())
                    .savedFilePath(board.getSavedFilePath())
                    .build();
            boardDtoList.add(boardDto);
        }

        return new PageImpl<>(boardDtoList, pageable, boardList.size());
    }
}
